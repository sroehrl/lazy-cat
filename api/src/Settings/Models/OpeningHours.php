<?php

namespace App\Settings\Models;

use Config\Transformers\JsonTransformation;
use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Attributes\Transform;
use Neoan\Model\Attributes\Type;
use Neoan\Model\Model;

class OpeningHours extends Model
{
    #[IsPrimaryKey]
    public int $id;

    #[Type('JSON')]
    public ?string $openingHoursCafe = null;

    #[Type('JSON')]
    public ?string $openingHoursLounge = null;

    #[Type('JSON')]
    public ?string $blockoutDays = null;

    #[Type('JSON')]
    public ?string $customHoursCafe = null;

    #[Type('JSON')]
    public ?string $customHoursLounge = null;

    public function asJson(): array
    {
        return [
            'openingHoursCafe' => $this->openingHoursCafe ? json_decode($this->openingHoursCafe,true) : [],
            'openingHoursLounge' => $this->openingHoursLounge ? json_decode($this->openingHoursLounge,true) : [],
            'blockoutDays' => $this->blockoutDays ? json_decode($this->blockoutDays,true) : [],
            'customHoursCafe' => $this->customHoursCafe ? json_decode($this->customHoursCafe,true) : [],
            'customHoursLounge' => $this->customHoursLounge ? json_decode($this->customHoursLounge,true) : [],
        ];
    }

}