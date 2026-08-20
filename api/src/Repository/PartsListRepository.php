<?php

namespace App\Repository;

use App\Entity\PartsList;
use App\Repository\Contract\SoftDeletableRepositoryInterface;
use App\Repository\Trait\SoftDeletableRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PartsList>
 */
class PartsListRepository extends ServiceEntityRepository implements SoftDeletableRepositoryInterface
{
    use SoftDeletableRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PartsList::class);
    }

    public function store(PartsList $partsList): PartsList
    {
        $this->getEntityManager()->persist($partsList);
        $this->getEntityManager()->flush();

        return $partsList;
    }

    public function persist(PartsList $partsList): void
    {
        $this->getEntityManager()->persist($partsList);
    }
}
