<?php

namespace App\Factory;

use App\DTO\Input\Order\StoreOrderInputDTO;
use App\DTO\Output\Order\OrderCollectionOutputDTO;
use App\DTO\Output\Order\OrderOutputDTO;
use App\DTO\Output\Pagination\MetaPaginationOutputDTO;
use App\Entity\Order;
use App\Entity\PartsList;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Entity\WorksList;
use Doctrine\ORM\EntityManagerInterface;

class OrderFactory
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private WorksListFactory $worksListFactory,
        private PartsListFactory $partsListFactory)
    {
    }

    public function makeOrder(StoreOrderInputDTO $storeOrderInputDTO): Order
    {
        $order = new Order();

        $order->setAddress($storeOrderInputDTO->address);
        $order->setWorkDatetime($storeOrderInputDTO->workDatetime);
        $order->setDescription($storeOrderInputDTO->description);
        $order->setStatus($storeOrderInputDTO->status);
        $order->setPayment($storeOrderInputDTO->payment);
        $order->setVehicle($storeOrderInputDTO->vehicle ? $this->entityManager->getReference(Vehicle::class, $storeOrderInputDTO->vehicle) : null);
        $order->setMaster($storeOrderInputDTO->master ? $this->entityManager->getReference(User::class, $storeOrderInputDTO->master) : null);
        $order->setClient($storeOrderInputDTO->client ? $this->entityManager->getReference(User::class, $storeOrderInputDTO->client) : null);
        $order->setWorksList($this->worksListFactory->makeStoreWorksListCollection($storeOrderInputDTO->worksList ?? []));
        $order->setPartsList($this->partsListFactory->makeStorePartsListCollection($storeOrderInputDTO->partsList ?? []));

        return $order;
    }

    public function makeStoreOrderInputDTO(array $data): StoreOrderInputDTO
    {
        $storeOrderInputDTO = new StoreOrderInputDTO();

        $storeOrderInputDTO->address = $data['address'] ?? null;
        $storeOrderInputDTO->workDatetime = isset($data['workDatetime']) ? new \DateTimeImmutable($data['workDatetime']) : null;
        $storeOrderInputDTO->description = $data['description'] ?? null;
        $storeOrderInputDTO->worksList = isset($data['worksList']) ? $this->worksListFactory->makeStoreWorksListInputDTOCollection($data['worksList']) : null;
        $storeOrderInputDTO->partsList = isset($data['partsList']) ? $this->partsListFactory->makeStorePartsListInputDTOCollection($data['partsList']) : null;
        $storeOrderInputDTO->status = $data['status'] ?? null;
        $storeOrderInputDTO->payment = $data['payment'] ?? null;
        $storeOrderInputDTO->vehicle = $data['vehicle'] ?? null;
        $storeOrderInputDTO->master = $data['master'] ?? null;
        $storeOrderInputDTO->client = $data['client'] ?? null;

        return $storeOrderInputDTO;
    }

    public function makeOrderOutputDTO(Order $order): OrderOutputDTO
    {
        $orderOutputDTO = new OrderOutputDTO();

        $orderOutputDTO->id = $order->getId();
        $orderOutputDTO->address = $order->getAddress();
        $orderOutputDTO->workDatetime = $order->getWorkDatetime();
        $orderOutputDTO->description = $order->getDescription();
        $orderOutputDTO->worksList = $this->worksListFactory->makeWorksListOutputDTOCollection($order->getWorksList());
        $orderOutputDTO->partsList = $this->partsListFactory->makePartsListOutputDTOCollection($order->getPartsList());
        $orderOutputDTO->vehicleId = $order->getVehicle()?->getId();
        $orderOutputDTO->status = $order->getStatus();
        $orderOutputDTO->masterId = $order->getMaster()?->getId();
        $orderOutputDTO->clientId = $order->getClient()?->getId();
        $orderOutputDTO->payment = $order->getPayment();
        $orderOutputDTO->createdAt = $order->getCreatedAt();
        $orderOutputDTO->updatedAt = $order->getUpdatedAt();

        return $orderOutputDTO;
    }

    public function makeOrderOutputDTOCollection(array $orders, MetaPaginationOutputDTO $meta): OrderCollectionOutputDTO
    {
        $orderCollectionOutputDTO = new OrderCollectionOutputDTO();

        $orderCollectionOutputDTO->data = array_map(fn($order) => $this->makeOrderOutputDTO($order), $orders);
        $orderCollectionOutputDTO->meta = $meta;

        return $orderCollectionOutputDTO;
    }
}
