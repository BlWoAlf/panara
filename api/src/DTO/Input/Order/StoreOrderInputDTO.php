<?php

namespace App\DTO\Input\Order;

use App\DTO\Input\StoreInputDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class StoreOrderInputDTO implements StoreInputDTOInterface
{
    #[Assert\NotBlank(allowNull: null)]
    #[Assert\Length(min: 2, max: 255)]
    public ?string $address = null;

    #[Assert\NotBlank(allowNull: null)]
    #[Assert\Type(\DateTimeImmutable::class)]
    public ?\DateTimeImmutable $workDatetime = null;

    #[Assert\NotBlank(allowNull: true)]
    public ?string $description = null;

    #[Assert\Type(type: 'array')]
    public ?array $worksList = null;

    #[Assert\Type(type: 'array')]
    public ?array $partsList = null;

    #[Assert\NotNull]
    #[Assert\Type(type: 'integer')]
    public ?int $status = null;

    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Type(type: 'float')]
    public ?float $payment = null;

    #[Assert\Type(type: 'integer')]
    public ?int $vehicle = null;

    #[Assert\Type(type: 'integer')]
    public ?int $master = null;

    #[Assert\Type(type: 'integer')]
    public ?int $client = null;
}