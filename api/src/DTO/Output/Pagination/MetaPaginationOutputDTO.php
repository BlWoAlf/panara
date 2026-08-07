<?php

namespace App\DTO\Output\Pagination;

use Symfony\Component\Serializer\Attribute\Groups;

class MetaPaginationOutputDTO
{
    #[Groups(groups: ['collection:paginate'])]
    public ?int $page = 1;

    #[Groups(groups: ['collection:paginate'])]
    public ?int $limit = 20;
}