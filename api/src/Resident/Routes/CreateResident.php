<?php

namespace App\Resident\Routes;

use App\Auth\Permissions\RequiresAdminPermission;
use App\Resident\Models\Resident;
use Neoan\Routing\Attributes\Post;
use Neoan\Routing\Interfaces\Routable;
use App\Resident\Requests\ResidentRequest;

#[Post('/api/resident')]
class CreateResident implements Routable
{
    public function __invoke(RequiresAdminPermission $permission, ResidentRequest $request): Resident
    {
        $resident = new Resident();
        $resident->name = $request->name;
        if (isset($request->gender)) {
            $resident->gender = $request->gender;
        }
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
        if (isset($request->status)) {
            $resident->status = $request->status;
        }
        if (isset($request->bornAt)) {
            $resident->bornAt = $request->bornAt;
        }
        return $resident->store();
    }
}