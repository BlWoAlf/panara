<?php

namespace App\Factory;

use App\DTO\Output\Order\OrderCollectionOutputDTO;
use App\DTO\Output\Order\OrderOutputDTO;
use App\DTO\Output\Pagination\MetaPaginationOutputDTO;
use App\Entity\Order;

class OrderFactory
{
    public function makeOrderOutputDTO(Order $order): OrderOutputDTO
    {
        $orderOutputDTO = new OrderOutputDTO();

        $orderOutputDTO->id = $order->getId();
        $orderOutputDTO->address = $order->getAddress();
        $orderOutputDTO->workDatetime = $order->getWorkDatetime();
        $orderOutputDTO->description = $order->getDescription();
        $orderOutputDTO->worksList = $order->getWorksList();
        $orderOutputDTO->partsList = $order->getPartsList();
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
