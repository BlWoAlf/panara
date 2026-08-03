<?php

namespace App\Entity\Contract;

interface SoftDeletableInterface
{
    public function getDeletedAt(): \DateTimeImmutable;

    public function setDeletedAt(\DateTimeImmutable $deletedAt): static;
}