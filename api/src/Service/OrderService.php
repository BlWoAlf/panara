<?php

namespace App\Service;

use App\DTO\Input\Order\StoreOrderInputDTO;
use App\Entity\Order;
use App\Factory\OrderFactory;
use App\Repository\OrderRepository;

class OrderService
{
    public function __construct(private OrderRepository $orderRepository, private OrderFactory $orderFactory)
    {}

    public function store(StoreOrderInputDTO $storeOrderInputDTO): Order
    {
        $order = $this->orderFactory->makeOrder($storeOrderInputDTO);
        return $this->orderRepository->store($order);
    }

    public function getOrders(int $page, int $limit, bool $total = false): array
    {
        $offset = ($page - 1) * $limit;

        if($total) {
            return [
                'items' => $this->orderRepository->findBy([], ['id' => 'DESC'], $limit, $offset),
                'total' => $this->orderRepository->count([]),
            ];
        }
        return $this->orderRepository->findBy([], ['id' => 'DESC'], $limit, $offset);
    }
}