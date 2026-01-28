<?php

namespace App\Member\Routes;

use App\Member\Models\MembershipPlan;
use App\Member\Requests\PlanRequest;
use Neoan\Routing\Attributes\Put;
use Neoan\Routing\Interfaces\Routable;
use Neoan\Request\Request;

use App\Auth\Permissions\RequiresAdminPermission;

#[Put('/api/plans/:id')]
class UpdatePlan implements Routable
{
    public function __invoke(RequiresAdminPermission $admin, PlanRequest $request): MembershipPlan
    {
        $id = Request::getParameter('id');
        $plan = MembershipPlan::get($id);
        $plan->name = $request->name;
        $plan->pricePerMonth = $request->pricePerMonth;
        $plan->description = $request->description;
        $plan->weekdays = $request->weekdays;
        $plan->weekends = $request->weekends;
        $plan->guest = $request->guest;
        $plan->visitationDiscount = $request->visitationDiscount;
        $plan->pawsPerMonth = $request->pawsPerMonth;
        return $plan->store();
    }
}
