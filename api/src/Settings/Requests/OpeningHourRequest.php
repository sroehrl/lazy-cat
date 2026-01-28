<?php

namespace App\Settings\Requests;

use Neoan\Request\RequestGuard;

class OpeningHourRequest extends RequestGuard
{
    public array $openingHoursCafe = [];
    public array $openingHoursLounge = [];
    public array $blockoutDays = [];
    public array $customHoursCafe = [];
    public array $customHoursLounge = [];

    public function asJson(array $input): string
    {
        return json_encode($input);
    }
}