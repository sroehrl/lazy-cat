<?php

namespace App\Resident\Requests;

use Neoan\Request\RequestGuard;
use Neoan\Model\Helper\DateTimeProperty;

class ResidentRequest extends RequestGuard
{
    public ?int $id = null;
    public string $name;
    public string $gender;
    public ?string $breed;
    public ?string $color;
    public string $description;
    public ?string $image;
    public string $status = 'available';

    public ?DateTimeProperty $bornAt = null;

}