<?php

namespace App\Support;

final class CategoryMapping
{
    public const GENDERS = ['zeny', 'muzi', 'deti'];

    /** Gender slug → DB category name */
    public const GENDER_NAMES = [
        'zeny' => 'Ženy',
        'muzi' => 'Muži',
        'deti' => 'Deti',
    ];

    public const CAT_SLUGS = ['oblecenie', 'topanky', 'doplnky'];

    /** Subcategory (product-type) slug → DB subcategory name */
    public const CAT_NAMES = [
        'oblecenie' => 'Oblečenie',
        'topanky'   => 'Topánky',
        'doplnky'   => 'Doplnky',
    ];

    public const SUB_SLUGS = ['novinky', 'oblecenie', 'topanky', 'doplnky', 'akcie'];

    public const STORE_SUB_NAV_ITEMS = [
        ['label' => 'Novinky', 'slug' => 'novinky'],
        ['label' => 'Oblečenie', 'slug' => 'oblecenie'],
        ['label' => 'Topánky', 'slug' => 'topanky'],
        ['label' => 'Doplnky', 'slug' => 'doplnky'],
    ];

    public const CLOTHING_SIZES = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

    public const SHOE_EU_SIZES = ['20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50'];

    public const ALL_SIZES = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50'];

    public const SIZE_ORDER = ['XS' => 0, 'S' => 1, 'M' => 2, 'L' => 3, 'XL' => 4, 'XXL' => 5];

    /** Subcategory names that use EU numeric shoe sizing */
    public const SHOE_SUBCATEGORY_NAMES = ['Topánky'];

    /** Category (gender) name → URL slug */
    public const GENDER_SLUG_BY_NAME = [
        'Ženy' => 'zeny',
        'Muži' => 'muzi',
        'Deti' => 'deti',
    ];

    /** Reverse of CAT_NAMES — used for breadcrumb slug lookup */
    public const CAT_SLUG_BY_NAME = [
        'Oblečenie' => 'oblecenie',
        'Topánky'   => 'topanky',
        'Doplnky'   => 'doplnky',
    ];
}
