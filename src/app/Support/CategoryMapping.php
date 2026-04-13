<?php

namespace App\Support;

final class CategoryMapping
{
    public const GENDERS = ['zeny', 'muzi', 'deti'];

    public const CAT_SLUGS = ['oblecenie', 'topanky', 'doplnky'];

    public const CAT_NAMES = [
        'oblecenie' => 'Oblečenie',
        'topanky'   => 'Topánky',
        'doplnky'   => 'Doplnky',
    ];

    public const SUB_SLUGS = ['novinky', 'oblecenie', 'topanky', 'doplnky', 'akcie'];

    public const SIZE_ORDER = ['XS' => 0, 'S' => 1, 'M' => 2, 'L' => 3, 'XL' => 4, 'XXL' => 5];

    public const ALL_SIZES = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

    /** Reverse of CAT_NAMES — used for breadcrumb slug lookup */
    public const CAT_SLUG_BY_NAME = [
        'Oblečenie' => 'oblecenie',
        'Topánky'   => 'topanky',
        'Doplnky'   => 'doplnky',
    ];
}
