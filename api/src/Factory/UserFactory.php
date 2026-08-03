<?php

namespace App\Factory;

use App\DTO\Input\User\StoreUserInputDTO;
use App\DTO\Output\User\UserOutputDTO;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class UserFactory
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function makeUser(StoreUserInputDTO $storeUserInputDTO): User
    {
        $user = new User();

        $user->setName($storeUserInputDTO->name);
        $user->setUuid(Uuid::v4()->toRfc4122());
        $user->setPassword($this->passwordHasher->hashPassword($user, $storeUserInputDTO->password));

        return $user;
    }

    public function makeStoreUserInputDTO(array $data): StoreUserInputDTO
    {
        $storeUserInputDTO = new StoreUserInputDTO();

        $storeUserInputDTO->name = $data['name'];
        $storeUserInputDTO->password = $data['password'];

        return $storeUserInputDTO;
    }

    public function makeUserOutputDTO(User $user): UserOutputDTO
    {
        $userOutputDTO = new UserOutputDTO();

        $userOutputDTO->id = $user->getId();
        $userOutputDTO->name = $user->getName();
        $userOutputDTO->userRoles = $user->getUserRoles();

        return $userOutputDTO;
    }

    public function makeRegisterUserOutputDTO(User $user, array $authData): UserOutputDTO
    {
        $userOutputDTO = new UserOutputDTO();

        $userOutputDTO->id = $user->getId();
        $userOutputDTO->name = $user->getName();
        $userOutputDTO->userRoles = $user->getUserRoles();
        $userOutputDTO->token = $authData['token'] ?? null;
        $userOutputDTO->refreshToken = $authData['refresh_token'] ?? null;

        return $userOutputDTO;
    }
}