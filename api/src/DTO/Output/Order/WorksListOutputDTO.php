<?php

namespace App\DTO\Output\Order;

use Symfony\Component\Serializer\Attribute\Groups;

class WorksListOutputDTO
{
    #[Groups(groups: ['order:item'])]
    public ?int $id = null;

    #[Groups(groups: ['order:item'])]
    public ?string $name = null;

    #[Groups(groups: ['order:item'])]
    public ?string $cost = null;

    #[Groups(groups: ['order:item'])]
    public ?int $time = null;
}