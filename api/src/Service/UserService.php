<?php

namespace App\Service;

use App\DTO\Input\User\StoreUserInputDTO;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Repository\UserRepository;

class UserService
{
    public function __construct(private UserRepository $userRepository, private UserFactory $userFactory)
    {
    }

    public function store(StoreUserInputDTO $storeUserInputDTO): User
    {
        $user = $this->userFactory->makeUser($storeUserInputDTO);
        return $this->userRepository->store($user);
    }
}