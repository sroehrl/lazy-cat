<?php

namespace App\Resident\Models;

use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Attributes\Type;
use Neoan\Model\Model;
use Neoan\Model\Traits\TimeStamps;
use Neoan\Model\Attributes\Transform;
use Neoan\Model\Helper\DateTimeProperty;

class Resident extends Model
{
    #[IsPrimaryKey]
    public int $id;

    public string $name;
    public string $gender;
    public ?string $breed;
    public ?string $color;
    public string $description;
    #[Type('text')]
    public ?string $image;
    public string $status = 'available';

    #[
        Type('datetime'),
        Transform(LockedTimeIn::class)
    ]
    public ?DateTimeProperty $bornAt = null;

    use TimeStamps;

}