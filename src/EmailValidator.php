<?php

declare(strict_types=1);

namespace Fabiensalles\PhpunitEmailValidator;

use GuzzleHttp\Client;

final class EmailValidator
{
    public function __construct(private readonly Client $client)
    {
    }

    public function validate(string $string): bool
    {
        $response = $this->client->get($string);

        $result = json_decode((string) $response->getBody(), true);

        return $result['format'];
    }
}
