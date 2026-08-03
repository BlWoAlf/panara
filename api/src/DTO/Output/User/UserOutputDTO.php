<?php

namespace App\DTO\Output\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Attribute\Groups;

class UserOutputDTO
{
    #[Groups(groups: ['user:item', 'user:register'])]
    public ?int $id = null;

    #[Groups(groups: ['user:item', 'user:register'])]
    public ?string $name = null;

    #[Groups(groups: ['user:item', 'user:register'])]
    public Collection $userRoles;

    #[Groups(groups: ['user:register'])]
    public ?string $token = null;

    #[Groups(groups: ['user:register'])]
    public ?string $refreshToken = null;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
    }
}