<?php

namespace App\Factory;

use App\DTO\Input\Order\StoreWorksListInputDTO;
use App\DTO\Output\Order\WorksListOutputDTO;
use App\Entity\WorksList;
use Doctrine\Common\Collections\Collection;

class WorksListFactory
{
    public function makeWorksListOutputDTO(WorksList $worksList): WorksListOutputDTO
    {
        $worksListOutputDTO = new WorksListOutputDTO();

        $worksListOutputDTO->id = $worksList->getId();
        $worksListOutputDTO->name = $worksList->getName();
        $worksListOutputDTO->cost = $worksList->getCost();
        $worksListOutputDTO->time = $worksList->getTime();

        return $worksListOutputDTO;
    }

    public function makeWorksListOutputDTOCollection(Collection $worksList): array
    {
        return array_map(fn (WorksList $item) => $this->makeWorksListOutputDTO($item), $worksList->toArray());
    }

    public function makeStoreWorksList(StoreWorksListInputDTO $storeWorksListInputDTO): WorksList
    {
        $worksList = new WorksList();

        $worksList->setName($storeWorksListInputDTO->name);
        $worksList->setCost($storeWorksListInputDTO->cost);
        $worksList->setTime($storeWorksListInputDTO->time);

        return $worksList;
    }

    public function makeStoreWorksListCollection(array $data): array
    {
        return array_map(fn ($worksList) => $this->makeStoreWorksList($worksList), $data);
    }

    public function makeStoreWorksListInputDTO(array $data): StoreWorksListInputDTO
    {
        $storeWorksListInputDTO = new StoreWorksListInputDTO();

        $storeWorksListInputDTO->name = $data['name'] ?? null;
        $storeWorksListInputDTO->cost = $data['cost'] ?? null;
        $storeWorksListInputDTO->time = $data['time'] ?? null;

        return $storeWorksListInputDTO;
    }

    public function makeStoreWorksListInputDTOCollection(array $data): array
    {
        return array_map(fn (array $part) => $this->makeStoreWorksListInputDTO($part), $data);
    }
}