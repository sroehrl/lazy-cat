<?php

namespace App\Lounge\Routes;

use Neoan\Database\Database;
use Neoan\Routing\Attributes\Get;
use Neoan\Routing\Interfaces\Routable;

#[Get('/api/lounge/occupancy')]
class GetOccupancy implements Routable
{
    public function __invoke(): array
    {
        $sql = 'SELECT SUM(pax) as reserved, slot FROM booking WHERE deletedAt IS NULL AND slot > NOW() GROUP BY slot';
        return Database::raw($sql, []);
    }
}