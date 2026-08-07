<?php

namespace App\Service;

use App\Repository\OrderRepository;

class OrderService
{
    public function __construct(private OrderRepository $orderRepository)
    {}

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