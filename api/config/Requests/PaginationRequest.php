<?php

namespace Config\Requests;

use Neoan\Request\RequestGuard;

class PaginationRequest extends RequestGuard
{
    public ?int $page = 1;
    public ?int $pageSize = 20;

    public ?string $sortOrder = 'ascending';

    public ?string $sortBy = 'id';

    public ?string $search = '';
}