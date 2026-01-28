<?php

namespace App\GiftCard\Models;

use App\Member\Models\Member;
use App\Transaction\Models\MoneyTransaction;
use Config\Transformers\PriceTransformation;
use Neoan\Model\Attributes\IsForeignKey;
use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Attributes\Transform;
use Neoan\Model\Model;
use Neoan\Model\Traits\TimeStamps;

class GiftCard extends Model
{
    #[IsPrimaryKey]
    public int $id;

    #[IsForeignKey(Member::class)]
    public ?int $memberId;

    #[IsForeignKey(MoneyTransaction::class)]
    public int $transactionId;


    public string $code;

    #[Transform(PriceTransformation::class)]
    public float $amount;

    use TimeStamps;

}