<?php

namespace App\Controller;

use App\DTOValidator\UserDTOValidator;
use App\Factory\UserFactory;
use App\ResponseBuilder\UserResponseBuilder;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{
    public function __construct(private UserFactory $userFactory,
                                private UserService $userService,
                                private UserResponseBuilder $userResponseBuilder,
                                private Security $security)
    {
    }

    #[Route('api/auth/register', name: 'auth_register', methods: ['POST'])]
    public function register(Request $request, UserDTOValidator $userDTOValidator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $storeUserInputDTO = $this->userFactory->makeStoreUserInputDTO($data);
        $userDTOValidator->validate($storeUserInputDTO);

        $user = $this->userService->store($storeUserInputDTO);

        $authResponse = $this->security->login($user, 'json_login', 'login');
        $authData = json_decode($authResponse->getContent(), true);

        return $this->userResponseBuilder->registerUserResponse($user, $authData);
    }

    #[Route('api/auth/me', name: 'auth_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        return $this->userResponseBuilder->userResponse($user);
    }
}
