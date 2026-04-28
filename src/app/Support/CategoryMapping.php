<?php

namespace App\Support;

final class CategoryMapping
{
    public const GENDERS = ['zeny', 'muzi', 'deti'];

    /** Gender slug → DB category enum value */
    public const GENDER_NAMES = [
        'zeny' => 'Ženy',
        'muzi' => 'Muži',
        'deti' => 'Deti',
    ];

    /** Category enum value → URL slug */
    public const GENDER_SLUG_BY_NAME = [
        'Ženy' => 'zeny',
        'Muži' => 'muzi',
        'Deti' => 'deti',
    ];

    public const CLOTHING_SIZES = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

    public const SHOE_EU_SIZES = ['20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50'];

    public const ALL_SIZES = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50'];

    public const SIZE_ORDER = ['XS' => 0, 'S' => 1, 'M' => 2, 'L' => 3, 'XL' => 4, 'XXL' => 5];

    /** Subcategory names that use EU numeric shoe sizing */
    public const SHOE_SUBCATEGORY_NAMES = ['Topánky'];
}
