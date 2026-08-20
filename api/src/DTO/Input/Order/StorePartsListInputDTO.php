<?php

namespace App\DTO\Input\Order;

use Symfony\Component\Validator\Constraints as Assert;

class StorePartsListInputDTO
{
    #[Assert\NotBlank]
    #[Assert\Type(type: 'string')]
    public ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Type(type: 'string')]
    public ?string $number = null;

    #[Assert\NotBlank]
    #[Assert\Type(type: 'float')]
    public ?float $cost = null;

    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    public ?int $count = null;
}