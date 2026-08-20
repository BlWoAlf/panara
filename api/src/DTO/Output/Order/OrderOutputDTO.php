<?php

namespace App\DTO\Output\Order;

use Symfony\Component\Serializer\Attribute\Groups;

class OrderOutputDTO
{
    #[Groups(groups: ['order:item'])]
    public ?int $id = null;

    #[Groups(groups: ['order:item'])]
    public ?string $address = null;

    #[Groups(groups: ['order:item'])]
    public ?\DateTimeImmutable $workDatetime = null;

    #[Groups(groups: ['order:item'])]
    public ?string $description = null;

    /**
     * @var WorksListOutputDTO[]|null
     */
    #[Groups(groups: ['order:item'])]
    public ?array $worksList = null;

    /**
     * @var PartsListOutputDTO[]|null
     */
    #[Groups(groups: ['order:item'])]
    public ?array $partsList = null;

    #[Groups(groups: ['order:item'])]
    public ?int $vehicleId = null;

    #[Groups(groups: ['order:item'])]
    public ?int $status = null;

    #[Groups(groups: ['order:item'])]
    public ?int $masterId = null;

    #[Groups(groups: ['order:item'])]
    public ?int $clientId = null;

    #[Groups(groups: ['order:item'])]
    public ?float $payment = null;

    #[Groups(groups: ['order:item'])]
    public ?\DateTimeImmutable $createdAt = null;

    #[Groups(groups: ['order:item'])]
    public ?\DateTimeImmutable $updatedAt = null;
}