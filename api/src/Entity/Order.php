<?php

namespace App\Entity;

use App\Entity\Contract\{SoftDeletableInterface, TimeStampsInterface};
use App\Entity\Trait\{SoftDeletableTrait, TimeStampsTrait};
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(type: Types::DECIMAL, precision: 18, scale: 2, nullable: true)]
    private ?string $payment = null;

    /**
     * @var Collection<int, PartsList>
     */
    #[ORM\OneToMany(targetEntity: PartsList::class, mappedBy: 'orderNumber')]
    private Collection $partsList;

    /**
     * @var Collection<int, WorksList>
     */
    #[ORM\OneToMany(targetEntity: WorksList::class, mappedBy: 'orderNumber')]
    private Collection $worksList;

    public function __construct()
    {
        $this->partsList = new ArrayCollection();
        $this->worksList = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, PartsList>
     */
    public function getPartsList(): Collection
    {
        return $this->partsList;
    }

    public function addPartsList(PartsList $partsList): static
    {
        if (!$this->partsList->contains($partsList)) {
            $this->partsList->add($partsList);
            $partsList->setOrderNumber($this);
        }

        return $this;
    }

    public function removePartsList(PartsList $partsList): static
    {
        if ($this->partsList->removeElement($partsList)) {
            // set the owning side to null (unless already changed)
            if ($partsList->getOrderNumber() === $this) {
                $partsList->setOrderNumber(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WorksList>
     */
    public function getWorksList(): Collection
    {
        return $this->worksList;
    }

    public function addWorksList(WorksList $worksList): static
    {
        if (!$this->worksList->contains($worksList)) {
            $this->worksList->add($worksList);
            $worksList->setOrderNumber($this);
        }

        return $this;
    }

    public function removeWorksList(WorksList $worksList): static
    {
        if ($this->worksList->removeElement($worksList)) {
            // set the owning side to null (unless already changed)
            if ($worksList->getOrderNumber() === $this) {
                $worksList->setOrderNumber(null);
            }
        }

        return $this;
    }

    public function setWorksList(array $worksListCollection): static
    {
        foreach ($worksListCollection as $worksList) {
            $this->addWorksList($worksList);
        }

        return $this;
    }

    public function setPartsList(array $partsListCollection): static
    {
        foreach ($partsListCollection as $partsList) {
            $this->addPartsList($partsList);
        }

        return $this;
    }
}
