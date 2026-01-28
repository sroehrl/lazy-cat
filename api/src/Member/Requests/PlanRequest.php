<?php

namespace App\Member\Requests;

use Neoan\Request\RequestGuard;

class PlanRequest extends RequestGuard
{
    public string $name;
    public float $pricePerMonth;
    public string $description;
    public bool $weekdays = true;
    public bool $weekends = false;
    public bool $guest = false;
    public float $visitationDiscount = 0;
    public int $pawsPerMonth = 1;


}
