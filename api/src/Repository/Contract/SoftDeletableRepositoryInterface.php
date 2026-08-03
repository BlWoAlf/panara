<?php

namespace App\Repository\Contract;

use App\Entity\Contract\SoftDeletableInterface;
use Doctrine\DBAL\LockMode;

Interface SoftDeletableRepositoryInterface
{
    public function delete(SoftDeletableInterface $softDeletable): void;

    public function find(mixed $id, LockMode|int|null $lockMode, int|null $lockVersion): ?object;

    public function findAll(): array;

    public function findBy(array $criteria, ?array $orderBy, ?int $limit, ?int $offset): array;

    public function findOneBy(array $criteria, ?array $orderBy): ?object;

    public function count(array $criteria): int;
}