<?php

namespace App\Member\Routes;

use App\Member\Models\MembershipPlan;
use Neoan\Request\Request;
use Neoan\Routing\Attributes\Get;
use Neoan\Routing\Interfaces\Routable;

#[Get('/api/plans')]
class ListPlans implements Routable
{
    public function __invoke(): array
    {
        $page = Request::getQuery('page') ?? 1;
        $pageSize = Request::getQuery('pageSize') ?? 10;
        return MembershipPlan::paginate($page, $pageSize)->ascending('pawsPerMonth')->where(['^deletedAt'])->get();
    }
}