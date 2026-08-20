<?php

namespace App\Repository;

use App\Entity\Order;
use App\Repository\Contract\SoftDeletableRepositoryInterface;
use App\Repository\Trait\SoftDeletableRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository implements SoftDeletableRepositoryInterface
{
    use SoftDeletableRepositoryTrait;

    public function __construct(
        ManagerRegistry $registry,
        private WorksListRepository $worksListRepository,
        private PartsListRepository $partsListRepository,
    ) {
        parent::__construct($registry, Order::class);
    }

    public function store(Order $order): Order
    {
        foreach ($order->getWorksList() as $worksList) {
            $this->worksListRepository->persist($worksList);
        }

        foreach ($order->getPartsList() as $partsList) {
            $this->partsListRepository->persist($partsList);
        }

        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();

        return $order;
    }
}
