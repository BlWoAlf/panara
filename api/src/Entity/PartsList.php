<?php

namespace App\Entity;

use App\Entity\Contract\SoftDeletableInterface;
use App\Entity\Trait\SoftDeletableTrait;
use App\Repository\PartsListRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartsListRepository::class)]
class PartsList implements SoftDeletableInterface
{
    use SoftDeletableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 18, scale: 2)]
    private ?string $cost = null;

    #[ORM\Column]
    private ?int $count = null;

    #[ORM\ManyToOne(inversedBy: 'partsList')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $orderNumber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getCost(): ?string
    {
        return $this->cost;
    }

    public function setCost(string $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): static
    {
        $this->count = $count;

        return $this;
    }

    public function getOrderNumber(): ?Order
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(?Order $orderNumber): static
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }
}
