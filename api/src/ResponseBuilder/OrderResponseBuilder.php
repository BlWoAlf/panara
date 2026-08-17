<?php

namespace App\ResponseBuilder;

use App\Entity\Order;
use App\Factory\OrderFactory;
use App\Factory\PaginationFactory;
use App\Resource\OrderResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderResponseBuilder
{
    public function __construct(private OrderFactory $orderFactory, private OrderResource $orderResource, private PaginationFactory $paginationFactory)
    {}

    public function orderResponse(Order $order, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $orderOutputDTO =  $this->orderFactory->makeOrderOutputDTO($order);

        $orderResource = $this->orderResource->order($orderOutputDTO, ['order:item']);
        return new JsonResponse($orderResource, $status, $headers, $isJson);
    }

    public function orderCollectionResponse(array $orders, array $meta, ?int $total = null, $status = 200, $headers = []): JsonResponse
    {
        $metaPaginationOutputDTO = $this->paginationFactory->makeMetaPaginationOutputDTO($meta);
        $orderOutputDTOCollection = $this->orderFactory->makeOrderOutputDTOCollection($orders, $metaPaginationOutputDTO);

        $orderCollectionResource = $this->orderResource->orders($orderOutputDTOCollection, ['order:item', 'collection:paginate']);

        return new JsonResponse($orderCollectionResource, $status, $headers);
    }
}