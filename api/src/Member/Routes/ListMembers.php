<?php

namespace App\Member\Routes;

use App\Auth\Permissions\RequiresAdminPermission;
use App\Member\Models\Member;
use Config\Requests\PaginationRequest;
use Neoan\Routing\Attributes\Get;
use Neoan\Routing\Interfaces\Routable;

#[Get('/api/members')]
class ListMembers implements Routable
{
    public function __invoke(RequiresAdminPermission $admin, PaginationRequest $request): array
    {
        $pagination = Member::paginate($request->page, $request->pageSize)->{$request->sortOrder}($request->sortBy);
        return $pagination->get();
    }
}