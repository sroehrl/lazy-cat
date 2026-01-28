<?php

namespace App\Member\Routes;

use App\Member\Models\MembershipPlan;
use Neoan\Routing\Attributes\Delete;
use Neoan\Routing\Interfaces\Routable;
use Neoan\Request\Request;

use App\Auth\Permissions\RequiresAdminPermission;

#[Delete('/api/plans/:id')]
class DeletePlan implements Routable
{
    public function __invoke(RequiresAdminPermission $admin): array
    {
        $id = Request::getParameter('id');
        $plan = MembershipPlan::get($id);
        $plan->delete();
        return ['success' => true];
    }
}
