<?php

namespace App\Transaction\Models;

use App\GiftCard\Models\GiftCard;
use App\Lounge\Models\Booking;
use App\Member\Models\Member;
use App\Product\Models\Product;
use Config\Transformers\PriceTransformation;
use Neoan\Model\Attributes\IsForeignKey;
use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Attributes\Transform;
use Neoan\Model\Model;
use Neoan\Model\Traits\TimeStamps;

class GiftCardTransaction extends Model
{
    #[IsPrimaryKey]
    public int $id;

    #[IsForeignKey(Member::class)]
    public ?int $memberId;
    #[IsForeignKey(Booking::class)]
    public ?int $bookingId;
    #[IsForeignKey(GiftCard::class)]
    public int $giftCardId;
    #[IsForeignKey(Product::class)]
    public ?int $productId;

    #[Transform(PriceTransformation::class)]
    public float $amount;

    public string $description;

    use TimeStamps;

}