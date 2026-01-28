<?php

namespace App\Product\Enums;

enum ProductType: string
{
    case PRODUCT = 'product';
    case SERVICE = 'service';
    case BOOKING = 'booking';

    case BEVERAGE = 'beverage';
    case SNACK = 'snack';

    case SWAG = 'swag';

    case ACCESSORY = 'accessory';

    case GIFT_CARD = 'gift-card';
    case OTHER = 'other';
}
