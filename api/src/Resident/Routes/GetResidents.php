<?php

namespace App\Resident\Routes;

use App\Resident\Models\Resident;
use Neoan\Model\Collection;
use Neoan\Routing\Attributes\Get;
use Neoan\Routing\Interfaces\Routable;

#[Get('/api/residents')]
class GetResidents implements Routable
{
    public function __invoke(): Collection
    {
        return Resident::retrieve(['^deletedAt', 'status' => '!adopted']);
    }
}