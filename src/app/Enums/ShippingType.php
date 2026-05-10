<?php

namespace App\Enums;

enum ShippingType: string
{
    case ADDRESS = 'doručenie na adresu';
    case PICKUP_POINT = 'výdajné miesto';
    case PERSONAL_PICKUP = 'osobný odber';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
