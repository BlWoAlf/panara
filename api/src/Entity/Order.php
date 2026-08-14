<?php

namespace App\Entity;

use App\Entity\Contract\{SoftDeletableInterface, TimeStampsInterface};
use App\Entity\Trait\{SoftDeletableTrait, TimeStampsTrait};
use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order implements SoftDeletableInterface, TimeStampsInterface
{
    use SoftDeletableTrait, TimeStampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $workDatetime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?array $worksList = null;

    #[ORM\Column(nullable: true)]
    private ?array $partsList = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Vehicle $vehicle = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $status = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $master = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $client = null;

    #[ORM\Column(nullable: true)]
    private ?float $payment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getWorkDatetime(): ?\DateTimeImmutable
    {
        return $this->workDatetime;
    }

    public function setWorkDatetime(\DateTimeImmutable $workDatetime): static
    {
        $this->workDatetime = $workDatetime;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getWorksList(): ?array
    {
        return $this->worksList;
    }

    public function setWorksList(?array $worksList): static
    {
        $this->worksList = $worksList;

        return $this;
    }

    public function getPartsList(): ?array
    {
        return $this->partsList;
    }

    public function setPartsList(?array $partsList): static
    {
        $this->partsList = $partsList;

        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): static
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getMaster(): ?User
    {
        return $this->master;
    }

    public function setMaster(?User $master): static
    {
        $this->master = $master;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getPayment(): ?float
    {
        return $this->payment;
    }

    public function setPayment(?float $payment): static
    {
        $this->payment = $payment;

        return $this;
    }
}
