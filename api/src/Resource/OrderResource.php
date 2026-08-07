<?php

namespace App\Resource;

use App\DTO\Output\Order\OrderCollectionOutputDTO;
use App\DTO\Output\Order\OrderOutputDTO;
use Symfony\Component\Serializer\SerializerInterface;

class OrderResource
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function order(OrderOutputDTO $order, $groups = [])
    {
        return $this->serializer->serialize($order, 'json', ['groups' => $groups]);
    }

    public function orders(OrderCollectionOutputDTO $orders, $groups = [])
    {
        return $this->serializer->normalize($orders, null, ['groups' => $groups]);
    }
}