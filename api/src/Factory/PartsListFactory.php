<?php

namespace App\Factory;

use App\DTO\Input\Order\StorePartsListInputDTO;
use App\DTO\Output\Order\PartsListOutputDTO;
use App\Entity\PartsList;
use Doctrine\Common\Collections\Collection;

class PartsListFactory
{
    public function makePartsListOutputDTO(PartsList $partsList): PartsListOutputDTO
    {
        $partsListOutputDTO = new PartsListOutputDTO();

        $partsListOutputDTO->id = $partsList->getId();
        $partsListOutputDTO->name = $partsList->getName();
        $partsListOutputDTO->number = $partsList->getNumber();
        $partsListOutputDTO->cost = $partsList->getCost();
        $partsListOutputDTO->count = $partsList->getCount();

        return $partsListOutputDTO;
    }

    public function makePartsListOutputDTOCollection(Collection $partsList): array
    {
        return array_map(fn (PartsList $item) => $this->makePartsListOutputDTO($item), $partsList->toArray());
    }

    public function makeStorePartsList(StorePartsListInputDTO $storePartsListInputDTO): PartsList
    {
        $partsList = new PartsList();

        $partsList->setName($storePartsListInputDTO->name);
        $partsList->setNumber($storePartsListInputDTO->number);
        $partsList->setCost($storePartsListInputDTO->cost);
        $partsList->setCount($storePartsListInputDTO->count);

        return $partsList;
    }

    public function makeStorePartsListCollection(array $data): array
    {
        return array_map(fn ($partsList) => $this->makeStorePartsList($partsList), $data);
    }

    public function makeStorePartsListInputDTO(array $data): StorePartsListInputDTO
    {
        $storePartsListInputDTO = new StorePartsListInputDTO();

        $storePartsListInputDTO->name = $data['name'] ?? null;
        $storePartsListInputDTO->number = $data['number'] ?? null;
        $storePartsListInputDTO->cost = $data['cost'] ?? null;
        $storePartsListInputDTO->count = $data['count'] ?? null;

        return $storePartsListInputDTO;
    }

    public function makeStorePartsListInputDTOCollection(array $data): array
    {
        return array_map(fn (array $part) => $this->makeStorePartsListInputDTO($part), $data);
    }
}