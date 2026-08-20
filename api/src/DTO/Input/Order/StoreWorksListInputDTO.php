<?php

namespace App\DTO\Input\Order;

use Symfony\Component\Validator\Constraints as Assert;

class StoreWorksListInputDTO
{
    #[Assert\NotBlank]
    #[Assert\Type(type: 'string')]
    public ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Type(type: 'float')]
    public ?float $cost = null;

    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    public ?int $time = null;
}