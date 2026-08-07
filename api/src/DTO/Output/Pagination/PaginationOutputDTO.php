<?php

namespace App\DTO\Output\Pagination;

use Symfony\Component\Serializer\Attribute\Groups;

class PaginationOutputDTO
{
    #[Groups(groups: ['collection:paginate'])]
    public ?array $data = [];

    #[Groups(groups: ['collection:paginate'])]
    public ?MetaPaginationOutputDTO $meta;
}