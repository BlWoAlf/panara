<?php

namespace App\ResponseBuilder;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Resource\UserResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserResponseBuilder
{
    public function __construct(private UserResource $userResource, private UserFactory $userFactory)
    {
    }

    public function userResponse(User $user, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $userOutputDTO = $this->userFactory->makeUserOutputDTO($user);

        $userResource = $this->userResource->user($userOutputDTO, ['user:item']);
        return new JsonResponse($userResource, $status, $headers, $isJson);
    }

    public function registerUserResponse(User $user, array $authData, $status = 201, $headers = [], $isJson = true): JsonResponse
    {
        $userOutputDTO = $this->userFactory->makeRegisterUserOutputDTO($user, $authData);

        $userResource = $this->userResource->user($userOutputDTO, ['user:register']);
        return new JsonResponse($userResource, $status, $headers, $isJson);
    }
}