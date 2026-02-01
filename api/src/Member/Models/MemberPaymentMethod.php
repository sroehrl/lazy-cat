<?php

namespace App\Member\Models;

use Neoan\Model\Attributes\IsForeignKey;
use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Model;
use Neoan\Model\Traits\TimeStamps;

class MemberPaymentMethod extends Model
{
    #[IsPrimaryKey]
    public int $id;

    #[IsForeignKey(Member::class)]
    public int $memberId;

    public string $tokenType;
    public string $status;
    public string $identifier;

    use TimeStamps;

}