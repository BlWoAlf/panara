<?php

namespace App\ResponseBuilder;

use App\Factory\OrderFactory;
use App\Factory\PaginationFactory;
use App\Resource\OrderResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderResponseBuilder
{
    public function __construct(private OrderFactory $orderFactory, private OrderResource $orderResource, private PaginationFactory $paginationFactory)
    {}

    public function orderCollectionResponse(array $orders, array $meta, int $total = null, $status = 200, $headers = []): JsonResponse
    {
        $metaPaginationOutputDTO = $this->paginationFactory->makeMetaPaginationOutputDTO($meta);
        $orderOutputDTOCollection = $this->orderFactory->makeOrderOutputDTOCollection($orders, $metaPaginationOutputDTO);

        $orderCollectionResource = $this->orderResource->orders($orderOutputDTOCollection, ['order:item', 'collection:paginate']);

        return new JsonResponse($orderCollectionResource, $status, $headers);
    }
}