<?php

namespace App\Repository;

use App\Entity\WorksList;
use App\Repository\Contract\SoftDeletableRepositoryInterface;
use App\Repository\Trait\SoftDeletableRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorksList>
 */
class WorksListRepository extends ServiceEntityRepository implements SoftDeletableRepositoryInterface
{
    use SoftDeletableRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorksList::class);
    }

    public function store(WorksList $worksList): WorksList
    {
        $this->getEntityManager()->persist($worksList);
        $this->getEntityManager()->flush();

        return $worksList;
    }

    public function persist(WorksList $worksList): void
    {
        $this->getEntityManager()->persist($worksList);
    }
}