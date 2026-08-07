<?php

namespace App\Factory;

use App\DTO\Output\Pagination\MetaPaginationOutputDTO;

class PaginationFactory
{
    public function makeMetaPaginationOutputDTO(array $meta): MetaPaginationOutputDTO
    {
        $metaPaginationOutputDTO = new MetaPaginationOutputDTO();

        $metaPaginationOutputDTO->page = $meta['page'];
        $metaPaginationOutputDTO->limit = $meta['limit'];

        return $metaPaginationOutputDTO;
    }
}