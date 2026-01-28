<?php

namespace App\Settings\Routes;

use App\Settings\Models\OpeningHours;
use Neoan\Routing\Attributes\Get;
use Neoan\Routing\Interfaces\Routable;


#[Get('/api/settings/opening-hours')]
class GetOpeningHours implements Routable
{
    public function __invoke(): array
    {
        return OpeningHours::retrieveOne([])->asJson();
    }

}