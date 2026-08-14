<?php

namespace App\DTO\Input\User;

use App\DTO\Input\StoreInputDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class StoreUserInputDTO implements StoreInputDTOInterface
{
    #[Assert\NotBlank(allowNull: null)]
    #[Assert\Length(min: 2, max: 255)]
    #[Assert\Regex(pattern: '/^[a-z_]+$/')]
    public ?string $name = null;

    #[Assert\NotBlank(allowNull: null)]
    #[Assert\Length(min: 6, max: 72)]
    #[Assert\Regex(pattern: '/^[^\s\x00-\x1F\x7F]+$/u')]
    public ?string $password = null;
}