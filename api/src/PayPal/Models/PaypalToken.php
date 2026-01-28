<?php

namespace App\PayPal\Models;

use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Model;
use Neoan\Model\Traits\TimeStamps;

class PaypalToken extends Model
{
    #[IsPrimaryKey]
    public int $id;

    public string $token;

    use TimeStamps;

}