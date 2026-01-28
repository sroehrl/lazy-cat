<?php

namespace App\Resident\Routes;

use App\Auth\Permissions\RequiresAdminPermission;
use App\Resident\Models\Resident;
use App\Resident\Requests\ResidentRequest;
use Neoan\Routing\Attributes\Put;
use Neoan\Routing\Interfaces\Routable;

#[Put('/api/resident/:id')]
class UpdateResident implements Routable
{
    public function __invoke(RequiresAdminPermission $permission, ResidentRequest $request): Resident
    {
        $resident = Resident::get($request->id);
        $resident->name = $request->name;
        $resident->gender = $request->gender;
        if (isset($request->breed)) {
            $resident->breed = $request->breed;
        }
        if (isset($request->color)) {
            $resident->color = $request->color;
        }
        if (isset($request->description)) {
            $resident->description = $request->description;
        }
        if (isset($request->image)) {
            $resident->image = $request->image;
        }
        $resident->status = $request->status;
        if (isset($request->bornAt)) {
            $resident->bornAt = $request->bornAt;
        }

        return $resident->store();
    }
}