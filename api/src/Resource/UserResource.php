<?php

namespace App\Resource;

use App\DTO\Output\User\UserOutputDTO;
use Symfony\Component\Serializer\SerializerInterface;

class UserResource
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function user(UserOutputDTO $user, $groups = [])
    {
        return $this->serializer->serialize($user, 'json', ['groups' => $groups]);
    }

    public function users()
    {}
}