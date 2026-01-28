<?php

namespace App\Member\Models;

use Neoan\Model\Attributes\Computed;
use Neoan\Model\Attributes\IsForeignKey;
use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Attributes\Transform;
use Neoan\Model\Attributes\Type;
use Neoan\Model\Model;
use Neoan\Model\Traits\TimeStamps;
use Neoan\Model\Transformers\LockedTimeIn;

class Membership extends Model
{
    #[IsPrimaryKey]
    public int $id;

    #[IsForeignKey(Member::class)]
    public int $memberId;

    #[IsForeignKey(MembershipPlan::class)]
    public int $planId;

    #[Computed]
    public function plan(): ?MembershipPlan
    {
        if(empty($this->planId)) return null;
        return MembershipPlan::get($this->planId);
    }
    #[
        Type('datetime'),
        Transform(LockedTimeIn::class)
    ]
    public string $startDate;
    #[
        Type('datetime'),
        Transform(LockedTimeIn::class)
    ]
    public string $endDate;
    use TimeStamps;

    public function byMemberId(int $memberId)
    {
        return self::retrieveOne(['memberId' => $memberId, '^deletedAt']);
    }

}