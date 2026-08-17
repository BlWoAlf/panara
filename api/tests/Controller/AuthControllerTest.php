<?php

namespace App\Tests\Controller;

use App\Tests\ApiWebTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class AuthControllerTest extends ApiWebTestCase
{
    public function testRegisterCreatesUserAndReturnsAuthData(): void
    {
        self::initializeDatabase();

        $client = static::createClient();
        $auth = $this->registerUser($client, 'john_doe', 'secret123');

        self::assertSame('john_doe', $auth['response']['name']);
        self::assertArrayHasKey('id', $auth['response']);
        self::assertArrayHasKey('userRoles', $auth['response']);
        self::assertNotEmpty($auth['token']);
        self::assertNotEmpty($auth['refreshToken']);
    }

    #[DataProvider('invalidRegisterPayloadProvider')]
    public function testRegisterReturnsValidationErrorForInvalidPayload(array $payload, string $field): void
    {
        self::initializeDatabase();

        $client = static::createClient();
        $this->jsonRequest($client, 'POST', '/api/auth/register', $payload);

        self::assertResponseStatusCodeSame(422);

        $response = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame('Ошибка валидации.', $response['message']);
        self::assertArrayHasKey('errors', $response);
        self::assertArrayHasKey($field, $response['errors']);
        self::assertNotEmpty($response['errors'][$field]);
    }

    public static function invalidRegisterPayloadProvider(): iterable
    {
        yield 'name is too short' => [
            'payload' => ['name' => 'a', 'password' => 'secret123'],
            'field' => 'name',
        ];

        yield 'name contains invalid characters' => [
            'payload' => ['name' => 'John Doe', 'password' => 'secret123'],
            'field' => 'name',
        ];

        yield 'password is too short' => [
            'payload' => ['name' => 'john_doe', 'password' => '12345'],
            'field' => 'password',
        ];

        yield 'name is missing' => [
            'payload' => ['password' => 'secret123'],
            'field' => 'name',
        ];

        yield 'password is missing' => [
            'payload' => ['name' => 'john_doe'],
            'field' => 'password',
        ];
    }

    public function testRegisterDoesNotAllowDuplicateName(): void
    {
        self::initializeDatabase();

        $client = static::createClient();
        $this->registerUser($client, 'duplicate_user', 'secret123');

        $this->jsonRequest($client, 'POST', '/api/auth/register', [
            'name' => 'duplicate_user',
            'password' => 'another_secret',
        ]);

        self::assertGreaterThanOrEqual(400, $client->getResponse()->getStatusCode());
    }

    public function testMeReturnsUnauthorizedWithoutToken(): void
    {
        self::initializeDatabase();

        $client = static::createClient();
        $client->request('GET', '/api/auth/me');

        self::assertResponseStatusCodeSame(401);
    }

    public function testMeReturnsCurrentUserWithValidToken(): void
    {
        self::initializeDatabase();

        $client = static::createClient();
        $auth = $this->registerUser($client, 'current_user', 'secret123');

        $this->jsonRequest($client, 'GET', '/api/auth/me', headers: [
            'Authorization' => 'Bearer '.$auth['token'],
        ]);

        self::assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame($auth['response']['id'], $response['id']);
        self::assertSame('current_user', $response['name']);
        self::assertArrayHasKey('userRoles', $response);
        self::assertArrayNotHasKey('token', $response);
        self::assertArrayNotHasKey('refreshToken', $response);
    }
}
