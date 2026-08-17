<?php

namespace App\Tests\Controller\Admin;

use App\Tests\ApiWebTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

final class OrderControllerTest extends ApiWebTestCase
{
    private function validOrderPayload(array $overrides = []): array
    {
        return array_replace([
            'address' => 'ул. Ленина, 10',
            'workDatetime' => '2026-08-20T10:00:00+00:00',
            'description' => 'Замена масла',
            'worksList' => [
                ['Замена масла' => 500, 'количество' => 1],
            ],
            'partsList' => [
                ['Масляный фильтр' => 800, 'количество' => 1],
            ],
            'status' => 1,
            'payment' => 1500.5,
        ], $overrides);
    }

    private function createOrder(KernelBrowser $client, string $token, array $overrides = []): array
    {
        $this->jsonRequest($client, 'POST', '/api/admin/order/store', $this->validOrderPayload($overrides), [
            'Authorization' => 'Bearer '.$token,
        ]);

        self::assertResponseIsSuccessful();

        /** @var array<string, mixed> $response */
        $response = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        return $response;
    }

    public function testIndexRequiresAuthentication(): void
    {
        self::initializeDatabase();

        $client = static::createClient();
        $client->request('GET', '/api/admin/orders');

        self::assertResponseStatusCodeSame(401);
    }

    public function testIndexIsForbiddenForNonAdmin(): void
    {
        self::initializeDatabase();

        $client = static::createClient();
        $auth = $this->registerUser($client);

        $this->jsonRequest($client, 'GET', '/api/admin/orders', headers: [
            'Authorization' => 'Bearer '.$auth['token'],
        ]);

        self::assertResponseStatusCodeSame(403);

        /** @var array<string, mixed> $response */
        $response = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame('Недостаточно прав для выполнения запроса.', $response['message']);
    }

    public function testIndexReturnsPaginatedOrders(): void
    {
        self::initializeDatabase();

        $client = static::createClient();
        $admin = $this->createAdminAuthHeader($client);

        $this->createOrder($client, $admin['token']);
        $this->createOrder($client, $admin['token']);
        $this->createOrder($client, $admin['token']);

        $this->jsonRequest($client, 'GET', '/api/admin/orders?page=1&limit=2', headers: [
            'Authorization' => 'Bearer '.$admin['token'],
        ]);

        self::assertResponseIsSuccessful();

        /** @var array<string, mixed> $response */
        $response = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('data', $response);
        self::assertCount(2, $response['data']);
        self::assertArrayHasKey('address', $response['data'][0]);
        self::assertArrayHasKey('status', $response['data'][0]);

        self::assertSame(1, $response['meta']['page']);
        self::assertSame(2, $response['meta']['limit']);
    }

    public function testStoreCreatesOrder(): void
    {
        self::initializeDatabase();

        $client = static::createClient();
        $admin = $this->createAdminAuthHeader($client);

        $order = $this->createOrder($client, $admin['token']);

        self::assertArrayHasKey('id', $order);
        self::assertSame('ул. Ленина, 10', $order['address']);
        self::assertSame('Замена масла', $order['description']);
        self::assertSame(1, $order['status']);
        self::assertSame(1500.5, $order['payment']);
        self::assertSame([['Замена масла' => 500, 'количество' => 1]], $order['worksList']);
        self::assertSame([['Масляный фильтр' => 800, 'количество' => 1]], $order['partsList']);
        self::assertNull($order['vehicleId']);
        self::assertNull($order['masterId']);
        self::assertNull($order['clientId']);
    }

    public function testStoreRequiresAuthentication(): void
    {
        self::initializeDatabase();

        $client = static::createClient();
        $this->jsonRequest($client, 'POST', '/api/admin/order/store', $this->validOrderPayload());

        self::assertResponseStatusCodeSame(401);
    }

    public function testStoreIsForbiddenForNonAdmin(): void
    {
        self::initializeDatabase();

        $client = static::createClient();
        $auth = $this->registerUser($client);

        $this->jsonRequest($client, 'POST', '/api/admin/order/store', $this->validOrderPayload(), [
            'Authorization' => 'Bearer '.$auth['token'],
        ]);

        self::assertResponseStatusCodeSame(403);
    }

    #[DataProvider('invalidOrderPayloadProvider')]
    public function testStoreReturnsValidationErrorForInvalidPayload(array $payload, string $field): void
    {
        self::initializeDatabase();

        $client = static::createClient();
        $admin = $this->createAdminAuthHeader($client);

        $this->jsonRequest($client, 'POST', '/api/admin/order/store', $payload, [
            'Authorization' => 'Bearer '.$admin['token'],
        ]);

        self::assertResponseStatusCodeSame(422);

        /** @var array<string, mixed> $response */
        $response = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame('Ошибка валидации.', $response['message']);
        self::assertArrayHasKey('errors', $response);
        self::assertArrayHasKey($field, $response['errors']);
        self::assertNotEmpty($response['errors'][$field]);
    }

    public static function invalidOrderPayloadProvider(): iterable
    {
        $valid = [
            'address' => 'ул. Ленина, 10',
            'workDatetime' => '2026-08-20T10:00:00+00:00',
            'status' => 1,
        ];

        yield 'address is missing' => [
            'payload' => array_diff_key($valid, ['address' => null]),
            'field' => 'address',
        ];

        yield 'address is too short' => [
            'payload' => array_merge($valid, ['address' => 'A']),
            'field' => 'address',
        ];

        yield 'workDatetime is missing' => [
            'payload' => array_diff_key($valid, ['workDatetime' => null]),
            'field' => 'workDatetime',
        ];

        yield 'status is missing' => [
            'payload' => array_diff_key($valid, ['status' => null]),
            'field' => 'status',
        ];
    }
}
