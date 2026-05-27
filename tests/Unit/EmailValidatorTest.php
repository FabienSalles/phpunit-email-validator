<?php

declare(strict_types=1);

namespace Fabiensalles\PhpunitEmailValidator\Tests\Unit;

use Fabiensalles\PhpunitEmailValidator\EmailValidator;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class EmailValidatorTest extends TestCase
{
    public function testCallApiWithCorrectUrl(): void
    {
        $mockHandler = new MockHandler([new Response(body: '{"format":true}')]);
        $emailValidator = new EmailValidator($this->createClient($mockHandler));

        $emailValidator->validate('your@example.com');

        self::assertEquals('https://email.verify/your@example.com', (string) $mockHandler->getLastRequest()->getUri());
    }

    #[DataProvider('provideFormatResult')]
    public function testReturnResult(string $format, bool $expectedResult): void
    {
        $mockHandler = new MockHandler([new Response(body: $format)]);
        $emailValidator = new EmailValidator($this->createClient($mockHandler));

        $result = $emailValidator->validate('your@example.com');

        self::assertSame($expectedResult, $result);
    }

    public static function provideFormatResult(): \Generator
    {
        yield 'format true' => [
            'format' => '{"format":true}',
            'expectedResult' => true
        ];

        yield 'format false' => [
            'format' => '{"format":false}',
            'expectedResult' => false
        ];
    }

    #[DataProvider('provideException')]
    public function testThrowException(int $statusCode, string $exceptionClass, string $message): void
    {
        $mockHandler = new MockHandler([new Response($statusCode)]);
        $emailValidator = new EmailValidator($this->createClient($mockHandler));

        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($message);

        $emailValidator->validate('your_email');
    }

    public static function provideException(): \Generator
    {
        yield '404 Not Found' => [404, ClientException::class, '404 Not Found'];
        yield '405 Method Not Allowed' => [405, ClientException::class, '405 Method Not Allowed'];
        yield '500 Internal Server Error' => [500, ServerException::class, 'Internal Server Error'];
    }

    private function createClient(MockHandler $mockHandler): Client
    {
        $client = new Client([
            'handler' => HandlerStack::create($mockHandler),
            'base_uri' => 'https://email.verify/'
        ]);

        return $client;
    }
}
