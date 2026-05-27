<?php

declare(strict_types=1);

namespace Fabiensalles\PhpunitEmailValidator\Tests\Integration;

use Fabiensalles\PhpunitEmailValidator\EmailValidator;
use GuzzleHttp\Client;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class EmailValidatorTest extends TestCase
{
    #[DataProvider('provideEmails')]
    public function testDisifyCall(string $email, bool $expectedResult): void
    {
        $client = new Client(['base_uri' => 'https://disify.com/api/email/']);
        $emailValidator = new EmailValidator($client);

        self::assertSame($expectedResult, $emailValidator->validate($email));
    }

    public static function provideEmails(): \Generator
    {
        yield 'valid email' => [
            'email' => 'test@example.com',
            'expectedResult' => true,
        ];

        yield 'invalid email' => [
            'email' => 'testexample.com',
            'expectedResult' => false,
        ];
    }
}
