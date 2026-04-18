<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Support\CategoryMapping;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->updateOrInsert(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $paymentMethods = [
            ['name' => 'Platba kartou online', 'type' => 'card',          'fee' => 0,    'sort_order' => 1],
            ['name' => 'Dobierka',              'type' => 'cod',           'fee' => 1.50, 'sort_order' => 2],
            ['name' => 'Google Pay',            'type' => 'google_pay',    'fee' => 0,    'sort_order' => 3],
            ['name' => 'Bankový prevod',        'type' => 'bank_transfer', 'fee' => 0,    'sort_order' => 4],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::firstOrCreate(['name' => $method['name']], $method);
        }

        DB::statement("UPDATE payment_methods SET requires_address = true WHERE type = 'cod'");

        $shippingMethods = [
            ['name' => 'Kuriér DPD',              'type' => 'address',         'price' => 3.99, 'delivery_days_from' => 2, 'delivery_days_to' => 3, 'sort_order' => 1],
            ['name' => 'Slovenská pošta',          'type' => 'address',         'price' => 2.49, 'delivery_days_from' => 3, 'delivery_days_to' => 5, 'sort_order' => 2],
            ['name' => 'Zásielkovňa',              'type' => 'pickup_point',    'price' => 1.99, 'delivery_days_from' => 2, 'delivery_days_to' => 4, 'sort_order' => 3],
            ['name' => 'Osobný odber Bratislava',  'type' => 'personal_pickup', 'price' => 0,    'delivery_days_from' => 1, 'delivery_days_to' => 1, 'sort_order' => 4],
        ];

        foreach ($shippingMethods as $method) {
            ShippingMethod::firstOrCreate(['name' => $method['name']], $method);
        }

        // Top-level categories = genders
        $categoryIds = [];
        $genders = ['Ženy', 'Muži', 'Deti'];
        foreach ($genders as $name) {
            $categoryIds[$name] = DB::table('categories')->insertGetId([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Subcategories = global product types
        $subcategoryIds = [];
        $subtypes = ['Novinky', 'Oblečenie', 'Topánky', 'Doplnky', 'Akcie'];
        foreach ($subtypes as $sub) {
            $subcategoryIds[$sub] = DB::table('subcategories')->insertGetId([
                'name' => $sub,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $brandIds = [];
        $brands = ['Zara', 'H&M', 'Mango', 'Reserved', 'CCC', 'Deichmann', 'Answear', 'Orsay'];
        foreach ($brands as $name) {
            $brandIds[$name] = DB::table('brands')->insertGetId([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $colorIds = [];
        $colors = [
            'Čierna' => '#000000',
            'Biela' => '#FFFFFF',
            'Červená' => '#FF0000',
            'Modrá' => '#0000FF',
            'Zelená' => '#008000',
            'Béžová' => '#F5F5DC',
            'Šedá' => '#808080',
            'Ružová' => '#FFC0CB',
            'Hnedá' => '#8B4513',
            'Žltá' => '#FFD700',
        ];
        foreach ($colors as $name => $hex) {
            $colorIds[$name] = DB::table('colors')->insertGetId([
                'name' => $name,
                'hex_code' => $hex,
            ]);
        }

        $materialIds = [];
        $materials = ['Bavlna', 'Polyester', 'Vlna', 'Hodváb', 'Denim', 'Koža', 'Viskóza', 'Ľan'];
        foreach ($materials as $name) {
            $materialIds[$name] = DB::table('materials')->insertGetId([
                'name' => $name,
            ]);
        }

        // cat = gender (Ženy/Muži/Deti), sub = product type (Oblečenie/Topánky/Doplnky)
        $products = [
            ['name' => 'Biele basic tričko',            'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'H&M',       'material' => 'Bavlna',   'featured' => true],
            ['name' => 'Čierne oversize tričko',         'cat' => 'Muži',  'sub' => 'Oblečenie', 'brand' => 'Zara',      'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Pruhované tričko námornícke',    'cat' => 'Deti',  'sub' => 'Oblečenie', 'brand' => 'Mango',     'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Tričko s potlačou vintage',      'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Reserved',  'material' => 'Polyester', 'featured' => false],
            ['name' => 'Letné kvetinové šaty',           'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Zara',      'material' => 'Viskóza',  'featured' => true],
            ['name' => 'Mini šaty s volánmi',            'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Mango',     'material' => 'Polyester', 'featured' => false],
            ['name' => 'Elegantné večerné šaty',         'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Orsay',     'material' => 'Hodváb',   'featured' => true],
            ['name' => 'Maxi šaty boho štýl',            'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Answear',   'material' => 'Ľan',      'featured' => false],
            ['name' => 'Puzdrové šaty midi',             'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Reserved',  'material' => 'Viskóza',  'featured' => false],
            ['name' => 'Skinny džínsy modré',            'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'H&M',       'material' => 'Denim',    'featured' => false],
            ['name' => 'Wide leg nohavice béžové',       'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Zara',      'material' => 'Ľan',      'featured' => true],
            ['name' => 'Jogger nohavice čierne',         'cat' => 'Muži',  'sub' => 'Oblečenie', 'brand' => 'Reserved',  'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Elegantné nohavice kárované',    'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Mango',     'material' => 'Vlna',     'featured' => false],
            ['name' => 'Vlnený kabát camel',             'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Mango',     'material' => 'Vlna',     'featured' => true],
            ['name' => 'Krátka bunda prešívaná',         'cat' => 'Muži',  'sub' => 'Oblečenie', 'brand' => 'Zara',      'material' => 'Polyester', 'featured' => false],
            ['name' => 'Trenčkot klasický béžový',       'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Reserved',  'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Oversized mikina sivá',          'cat' => 'Muži',  'sub' => 'Oblečenie', 'brand' => 'H&M',       'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Crop mikina ružová',             'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Answear',   'material' => 'Bavlna',   'featured' => true],
            ['name' => 'Zipová mikina čierna',           'cat' => 'Muži',  'sub' => 'Oblečenie', 'brand' => 'Reserved',  'material' => 'Polyester', 'featured' => false],
            ['name' => 'Biele kožené tenisky',           'cat' => 'Ženy',  'sub' => 'Topánky',   'brand' => 'CCC',       'material' => 'Koža',     'featured' => true],
            ['name' => 'Chunky sneakers čierne',         'cat' => 'Muži',  'sub' => 'Topánky',   'brand' => 'Deichmann', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Canvas tenisky farebné',         'cat' => 'Deti',  'sub' => 'Topánky',   'brand' => 'CCC',       'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Stiletto lodičky čierne',        'cat' => 'Ženy',  'sub' => 'Topánky',   'brand' => 'Deichmann', 'material' => 'Koža',     'featured' => false],
            ['name' => 'Nude lodičky kitten heel',       'cat' => 'Ženy',  'sub' => 'Topánky',   'brand' => 'CCC',       'material' => 'Koža',     'featured' => true],
            ['name' => 'Lodičky so šnurovaním',          'cat' => 'Ženy',  'sub' => 'Topánky',   'brand' => 'Answear',   'material' => 'Koža',     'featured' => false],
            ['name' => 'Vysoké čižmy nad koleno',        'cat' => 'Ženy',  'sub' => 'Topánky',   'brand' => 'CCC',       'material' => 'Koža',     'featured' => true],
            ['name' => 'Chelsea boots hnedé',            'cat' => 'Muži',  'sub' => 'Topánky',   'brand' => 'Deichmann', 'material' => 'Koža',     'featured' => false],
            ['name' => 'Kotníkové čižmy s prackou',      'cat' => 'Ženy',  'sub' => 'Topánky',   'brand' => 'Answear',   'material' => 'Koža',     'featured' => false],
            ['name' => 'Kožené sandále ploché',          'cat' => 'Ženy',  'sub' => 'Topánky',   'brand' => 'CCC',       'material' => 'Koža',     'featured' => false],
            ['name' => 'Wedge sandále espadrilky',       'cat' => 'Ženy',  'sub' => 'Topánky',   'brand' => 'Deichmann', 'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Crossbody kabelka mini',         'cat' => 'Ženy',  'sub' => 'Doplnky',   'brand' => 'Zara',      'material' => 'Koža',     'featured' => true],
            ['name' => 'Tote bag plátená veľká',         'cat' => 'Ženy',  'sub' => 'Doplnky',   'brand' => 'H&M',       'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Clutch zlatá večerná',           'cat' => 'Ženy',  'sub' => 'Doplnky',   'brand' => 'Mango',     'material' => 'Koža',     'featured' => false],
            ['name' => 'Shopper kabelka kvetinová',      'cat' => 'Ženy',  'sub' => 'Doplnky',   'brand' => 'Reserved',  'material' => 'Polyester', 'featured' => false],
            ['name' => 'Bucket bag semišová',            'cat' => 'Ženy',  'sub' => 'Doplnky',   'brand' => 'Answear',   'material' => 'Koža',     'featured' => true],
            ['name' => 'Kožený opasok čierny',           'cat' => 'Muži',  'sub' => 'Doplnky',   'brand' => 'Zara',      'material' => 'Koža',     'featured' => false],
            ['name' => 'Elastický opasok zlatá spona',   'cat' => 'Ženy',  'sub' => 'Doplnky',   'brand' => 'Mango',     'material' => 'Polyester', 'featured' => false],
            ['name' => 'Hodvábna šatka kvetinová',       'cat' => 'Ženy',  'sub' => 'Doplnky',   'brand' => 'Mango',     'material' => 'Hodváb',   'featured' => true],
            ['name' => 'Pletená vlnená šatka',           'cat' => 'Ženy',  'sub' => 'Doplnky',   'brand' => 'H&M',       'material' => 'Vlna',     'featured' => false],
            ['name' => 'Vzorovaná šatka vintage',        'cat' => 'Ženy',  'sub' => 'Doplnky',   'brand' => 'Reserved',  'material' => 'Viskóza',  'featured' => false],
            ['name' => 'Základné bavlnené tričko',       'cat' => 'Muži',  'sub' => 'Oblečenie', 'brand' => 'Reserved',  'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Tričko s dlhým rukávom',         'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Mango',     'material' => 'Bavlna',   'featured' => true],
            ['name' => 'Športové funkčné tričko',        'cat' => 'Muži',  'sub' => 'Oblečenie', 'brand' => 'Answear',   'material' => 'Polyester', 'featured' => false],
            ['name' => 'Polo tričko tmavomodré',         'cat' => 'Muži',  'sub' => 'Oblečenie', 'brand' => 'Zara',      'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Grafické tričko s nápisom',      'cat' => 'Deti',  'sub' => 'Oblečenie', 'brand' => 'H&M',       'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Pletené zimné šaty',             'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Mango',     'material' => 'Vlna',     'featured' => true],
            ['name' => 'Košeľové šaty khaki',            'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Reserved',  'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Tee šaty ležérne',               'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'H&M',       'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Koktejlové šaty čierne',         'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Zara',      'material' => 'Viskóza',  'featured' => true],
            ['name' => 'Plážové šaty ľahké',             'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Orsay',     'material' => 'Polyester', 'featured' => false],
            ['name' => 'Cargo nohavice zelené',          'cat' => 'Muži',  'sub' => 'Oblečenie', 'brand' => 'Zara',      'material' => 'Bavlna',   'featured' => true],
            ['name' => 'Chino nohavice svetlé',          'cat' => 'Muži',  'sub' => 'Oblečenie', 'brand' => 'H&M',       'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Zamatové nohavice',              'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Reserved',  'material' => 'Viskóza',  'featured' => false],
            ['name' => 'Mom jeans vintage',              'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Answear',   'material' => 'Denim',    'featured' => true],
            ['name' => 'Zvonové džínsy',                 'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Mango',     'material' => 'Denim',    'featured' => false],
            ['name' => 'Dlhý zimný kabát s kapucňou',    'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Zara',      'material' => 'Vlna',     'featured' => false],
            ['name' => 'Parka khaki s kožušinou',        'cat' => 'Muži',  'sub' => 'Oblečenie', 'brand' => 'H&M',       'material' => 'Polyester', 'featured' => true],
            ['name' => 'Falošná kožená bunda',           'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Mango',     'material' => 'Koža',     'featured' => false],
            ['name' => 'Riflová bunda klasická',         'cat' => 'Muži',  'sub' => 'Oblečenie', 'brand' => 'Reserved',  'material' => 'Denim',    'featured' => false],
            ['name' => 'Mikina s kapucňou basic',        'cat' => 'Deti',  'sub' => 'Oblečenie', 'brand' => 'H&M',       'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Fleece mikina teplá',            'cat' => 'Muži',  'sub' => 'Oblečenie', 'brand' => 'Answear',   'material' => 'Polyester', 'featured' => true],
            ['name' => 'Dlhá mikina ako šaty',           'cat' => 'Ženy',  'sub' => 'Oblečenie', 'brand' => 'Zara',      'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Slip-on tenisky čierne',         'cat' => 'Deti',  'sub' => 'Topánky',   'brand' => 'Deichmann', 'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Športové bežecké tenisky',       'cat' => 'Muži',  'sub' => 'Topánky',   'brand' => 'CCC',       'material' => 'Polyester', 'featured' => true],
            ['name' => 'High-top tenisky kožené',        'cat' => 'Muži',  'sub' => 'Topánky',   'brand' => 'Answear',   'material' => 'Koža',     'featured' => false],
            ['name' => 'Červené lodičky lakované',       'cat' => 'Ženy',  'sub' => 'Topánky',   'brand' => 'Deichmann', 'material' => 'Koža',     'featured' => true],
            ['name' => 'Slingback lodičky perleťové',    'cat' => 'Ženy',  'sub' => 'Topánky',   'brand' => 'CCC',       'material' => 'Koža',     'featured' => false],
            ['name' => 'Platformové lodičky párty',      'cat' => 'Ženy',  'sub' => 'Topánky',   'brand' => 'Answear',   'material' => 'Polyester', 'featured' => false],
            ['name' => 'Biker boots nízke',              'cat' => 'Muži',  'sub' => 'Topánky',   'brand' => 'Deichmann', 'material' => 'Koža',     'featured' => true],
            ['name' => 'Zimné snehule s kožušinou',      'cat' => 'Deti',  'sub' => 'Topánky',   'brand' => 'CCC',       'material' => 'Polyester', 'featured' => false],
            ['name' => 'Kovbojské čižmy hnedé',          'cat' => 'Ženy',  'sub' => 'Topánky',   'brand' => 'Answear',   'material' => 'Koža',     'featured' => true],
            ['name' => 'Platformové sandále leto',       'cat' => 'Ženy',  'sub' => 'Topánky',   'brand' => 'Deichmann', 'material' => 'Koža',     'featured' => false],
            ['name' => 'Rímske sandále šnurovacie',      'cat' => 'Ženy',  'sub' => 'Topánky',   'brand' => 'CCC',       'material' => 'Koža',     'featured' => false],
            ['name' => 'Šľapky k vode neonové',          'cat' => 'Deti',  'sub' => 'Topánky',   'brand' => 'Answear',   'material' => 'Polyester', 'featured' => false],
            ['name' => 'Mestský elegantný ruksak',       'cat' => 'Muži',  'sub' => 'Doplnky',   'brand' => 'Zara',      'material' => 'Koža',     'featured' => false],
            ['name' => 'Športová ľadvinka',              'cat' => 'Muži',  'sub' => 'Doplnky',   'brand' => 'H&M',       'material' => 'Polyester', 'featured' => false],
            ['name' => 'Listová kabelka strieborná',     'cat' => 'Ženy',  'sub' => 'Doplnky',   'brand' => 'Mango',     'material' => 'Polyester', 'featured' => true],
            ['name' => 'Kabelka s masívnou reťazou',     'cat' => 'Ženy',  'sub' => 'Doplnky',   'brand' => 'Reserved',  'material' => 'Koža',     'featured' => false],
            ['name' => 'Tenký kožený opasok biely',      'cat' => 'Ženy',  'sub' => 'Doplnky',   'brand' => 'Zara',      'material' => 'Koža',     'featured' => false],
            ['name' => 'Látkový opasok s prackou',       'cat' => 'Muži',  'sub' => 'Doplnky',   'brand' => 'H&M',       'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Pruhovaná letná šatka',          'cat' => 'Ženy',  'sub' => 'Doplnky',   'brand' => 'Mango',     'material' => 'Bavlna',   'featured' => false],
            ['name' => 'Teplý zimný šál biely',          'cat' => 'Ženy',  'sub' => 'Doplnky',   'brand' => 'H&M',       'material' => 'Vlna',     'featured' => true],
        ];

        $colorList = array_keys($colorIds);

        // Images keyed by product type (sub)
        $imagePaths = [
            'Oblečenie' => [
                'images/products/dress.jpg',
                'images/products/satin-blouse.jpg',
            ],
            'Topánky' => [
                'images/products/boots.jpeg',
                'images/products/satin-blouse.jpg',
            ],
            'Doplnky' => [
                'images/products/satin-blouse.jpg',
                'images/products/dress.jpg',
            ],
        ];

        foreach ($products as $index => $p) {
            $slug = Str::slug($p['name']).'-'.($index + 1);

            $descriptions = [
                'Kvalitný produkt z kolekcie '.$p['brand'].'. Vyrobený z materiálu '.$p['material'].'.',
                'Štýlový kúsok pre modernú ženu. Perfektný do každej príležitosti.',
                'Trendy dizajn inšpirovaný aktuálnymi módnymi trendmi zo svetových módnych týždňov.',
                'Komfortný a elegantný produkt vhodný pre každodennné nosenie.',
                'Prémiová kvalita za dostupnú cenu. Ideálny doplnok do vášho šatníka.',
            ];

            $productId = DB::table('products')->insertGetId([
                'name' => $p['name'],
                'slug' => $slug,
                'description' => $descriptions[$index % count($descriptions)],
                'category_id' => $categoryIds[$p['cat']],
                'subcategory_id' => $subcategoryIds[$p['sub']],
                'brand_id' => $brandIds[$p['brand']],
                'material_id' => $materialIds[$p['material']],
                'is_featured' => $p['featured'] ? 'true' : 'false',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $numColors = rand(2, 3);
            $productColors = array_slice($colorList, $index % count($colorList), $numColors);

            $isShoe = in_array($p['sub'], CategoryMapping::SHOE_SUBCATEGORY_NAMES);
            $sizePool = $isShoe ? CategoryMapping::SHOE_EU_SIZES : CategoryMapping::CLOTHING_SIZES;

            foreach ($productColors as $colorName) {
                $numSizes = rand(3, 5);
                $maxOffset = count($sizePool) - $numSizes;
                $productSizes = array_slice($sizePool, rand(0, $maxOffset), $numSizes);
                $basePrice = round(rand(1490, 9990) / 100) * 100 / 100;

                foreach ($productSizes as $size) {
                    DB::table('product_variants')->insert([
                        'product_id' => $productId,
                        'color_id' => $colorIds[$colorName],
                        'size' => $size,
                        'price' => $basePrice,
                        'stock_quantity' => rand(0, 50),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $catImages = $imagePaths[$p['sub']];
            $numImages = rand(2, 3);
            for ($i = 0; $i < $numImages; $i++) {
                DB::table('product_images')->insert([
                    'product_id' => $productId,
                    'image_path' => $catImages[$i % count($catImages)],
                    'is_primary' => $i === 0 ? 'true' : 'false',
                    'sort_order' => $i,
                    'created_at' => now(),
                ]);
            }
        }
    }
}
