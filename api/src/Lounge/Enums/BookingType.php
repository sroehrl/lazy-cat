<?php

namespace App\Lounge\Enums;

enum BookingType: string
{
    case POINT_OF_SALE = 'pos';
    case CREDIT_CARD = 'credit card';

    case GIFT_CARD = 'gift-card';

    case MEMBERSHIP = 'membership';
}
