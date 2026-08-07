<?php

namespace App\Controller\Admin;

use App\ResponseBuilder\OrderResponseBuilder;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    public function __construct(
        private OrderService $orderService,
        private OrderResponseBuilder $orderResponseBuilder)
    {
    }

    #[Route('api/admin/orders', name: 'admin_order_list', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $meta = [
            'page' => $request->query->getInt('page', 1),
            'limit' => $request->query->getInt('limit', 20)
        ];

        $result = $this->orderService->getOrders($meta['page'], $meta['limit']);

        return $this->orderResponseBuilder->orderCollectionResponse($result, $meta);
    }

    #[Route('api/admin/orders/store', name: 'admin_order_store', methods: ['PUT'])]
    public function store(Request $request): JsonResponse
    {

        return new JsonResponse();
    }
}
