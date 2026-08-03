<?php

namespace App\Repository\Trait;

use App\Entity\Contract\SoftDeletableInterface;
use Doctrine\DBAL\LockMode;

trait SoftDeletableRepositoryTrait
{
    public function delete(SoftDeletableInterface $softDeletable): void
    {
        $softDeletable->setDeletedAt(new \DateTimeImmutable());

        $this->getEntityManager()->persist($softDeletable);
        $this->getEntityManager()->flush();
    }

    public function find(mixed $id, LockMode|int|null $lockMode = null, int|null $lockVersion = null): ?object
    {
        $entity = parent::find($id, $lockMode, $lockVersion);

        if ($entity instanceof SoftDeletableInterface && $entity->getDeletedAt() !== null) {
            return null;
        }

        return $entity;
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return parent::findBy([...$criteria, 'deletedAt' => null], $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria, ?array $orderBy = null): ?object
    {
        return parent::findOneBy([...$criteria, 'deletedAt' => null], $orderBy);
    }

    public function count(array $criteria = []): int
    {
        return parent::count([...$criteria, 'deletedAt' => null]);
    }
}