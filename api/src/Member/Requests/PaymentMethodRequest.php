<?php

namespace App\Member\Requests;

use Neoan\Request\RequestGuard;

class PaymentMethodRequest extends RequestGuard
{
    public ?int $memberId;
    public string $tokenType;
    public string $status;
    public string $identifier;

}