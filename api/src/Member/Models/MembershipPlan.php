<?php

namespace App\Member\Models;

use Config\Transformers\PriceTransformation;
use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Attributes\Transform;
use Neoan\Model\Attributes\Type;
use Neoan\Model\Model;
use Neoan\Model\Traits\TimeStamps;

class MembershipPlan extends Model
{
    #[IsPrimaryKey]
    public int $id;

    public string $name;
    #[
        Transform(PriceTransformation::class),
        Type('int', 11)
    ]
    public float $pricePerMonth = 0;

    public string $description;

    public bool $weekdays = true;
    public bool $weekends = false;

    public bool $guest = false;

    #[
        Transform(PriceTransformation::class),
        Type('int', 11)
    ]
    public float $visitationDiscount = 0;

    public int $pawsPerMonth = 1;


    use TimeStamps;

}