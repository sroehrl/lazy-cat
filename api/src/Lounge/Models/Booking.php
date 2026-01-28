<?php

namespace App\Lounge\Models;

use App\Lounge\Enums\BookingType;
use App\Member\Models\Member;
use Neoan\Model\Attributes\IsEnum;
use Neoan\Model\Attributes\IsForeignKey;
use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Attributes\Transform;
use Neoan\Model\Attributes\Type;
use Neoan\Model\Helper\DateTimeProperty;
use Neoan\Model\Model;
use Neoan\Model\Traits\TimeStamps;
use Neoan\Model\Transformers\LockedTimeIn;

class Booking extends Model
{
    #[IsPrimaryKey]
    public int $id;

    #[IsForeignKey(Member::class)]
    public ?int $memberId;

    public ?string $name;

    #[IsEnum(BookingType::class)]
    public ?BookingType $type = null;

    public int $pax = 1;

    public ?int $transactionId;

    #[
        Type('datetime'),
        Transform(LockedTimeIn::class)
    ]
    public DateTimeProperty $slot;

    use TimeStamps;
}