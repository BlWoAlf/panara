<?php

namespace App\Tests;

use App\DTO\Input\User\StoreUserInputDTO;
use App\Entity\Role;
use App\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApiWebTestCase extends WebTestCase
{
    private static bool $databaseInitialized = false;

    protected static function initializeDatabase(): void
    {
        if (self::$databaseInitialized) {
            return;
        }

        self::bootKernel();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        self::$databaseInitialized = true;
        self::ensureKernelShutdown();
    }

    protected function jsonRequest(
        KernelBrowser $client,
        string $method,
        string $uri,
        ?array $data = null,
        array $headers = [],
    ): void {
        $server = ['CONTENT_TYPE' => 'application/json'];

        foreach ($headers as $name => $value) {
            $server['HTTP_'.strtoupper(str_replace('-', '_', $name))] = $value;
        }

        $client->request(
            $method,
            $uri,
            server: $server,
            content: $data !== null ? json_encode($data, JSON_THROW_ON_ERROR) : null,
        );
    }

    /**
     * @return array{
     *     name: string,
     *     password: string,
     *     response: array<string, mixed>,
     *     token: string,
     *     refreshToken: string
     * }
     */
    protected function registerUser(
        KernelBrowser $client,
        ?string $name = null,
        ?string $password = null,
    ): array {
        $name ??= 'test_user_'.self::randomLowercaseId();
        $password ??= 'secret123';

        $this->jsonRequest($client, 'POST', '/api/auth/register', [
            'name' => $name,
            'password' => $password,
        ]);

        self::assertResponseStatusCodeSame(201);

        /** @var array<string, mixed> $response */
        $response = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('token', $response);
        self::assertArrayHasKey('refreshToken', $response);

        return [
            'name' => $name,
            'password' => $password,
            'response' => $response,
            'token' => $response['token'],
            'refreshToken' => $response['refreshToken'],
        ];
    }

    /**
     * Creates a user with ROLE_ADMIN and logs them in, bypassing the (non-admin) registration endpoint.
     *
     * @return array{name: string, password: string, token: string}
     */
    protected function createAdminAuthHeader(
        KernelBrowser $client,
        ?string $name = null,
        ?string $password = null,
    ): array {
        $name ??= 'admin_'.self::randomLowercaseId();
        $password ??= 'secret123';

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $userFactory = self::getContainer()->get(UserFactory::class);

        $role = $entityManager->getRepository(Role::class)->findOneBy(['code' => 'ROLE_ADMIN']);

        if (!$role) {
            $role = new Role();
            $role->setName('Administrator');
            $role->setCode('ROLE_ADMIN');
            $entityManager->persist($role);
        }

        $storeUserInputDTO = new StoreUserInputDTO();
        $storeUserInputDTO->name = $name;
        $storeUserInputDTO->password = $password;

        $user = $userFactory->makeUser($storeUserInputDTO);
        $user->addUserRole($role);

        $entityManager->persist($user);
        $entityManager->flush();

        $this->jsonRequest($client, 'POST', '/api/auth/login', [
            'name' => $name,
            'password' => $password,
        ]);

        self::assertResponseIsSuccessful();

        /** @var array<string, mixed> $response */
        $response = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('token', $response);

        return [
            'name' => $name,
            'password' => $password,
            'token' => $response['token'],
        ];
    }

    /**
     * User names only allow lowercase Latin letters and underscores, so uniqid() (which contains digits) can't be
     * used directly as/in a name.
     */
    private static function randomLowercaseId(): string
    {
        return strtr(uniqid(), '0123456789', 'abcdefghij');
    }
}
