<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $categoryIds = [];
        $categories = ['Oblečenie', 'Topánky', 'Doplnky'];
        foreach ($categories as $name) {
            $categoryIds[$name] = DB::table('categories')->insertGetId([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Subcategories
        $subcategoryIds = [];
        $subcategories = [
            'Oblečenie' => ['Tričká', 'Šaty', 'Nohavice', 'Kabáty', 'Mikiny'],
            'Topánky'   => ['Tenisky', 'Lodičky', 'Čižmy', 'Sandále'],
            'Doplnky'   => ['Kabelky', 'Opasky', 'Šatky'],
        ];
        foreach ($subcategories as $cat => $subs) {
            foreach ($subs as $sub) {
                $subcategoryIds[$sub] = DB::table('subcategories')->insertGetId([
                    'name' => $sub,
                    'category_id' => $categoryIds[$cat],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Brands
        $brandIds = [];
        $brands = ['Zara', 'H&M', 'Mango', 'Reserved', 'CCC', 'Deichmann', 'Answear', 'Orsay'];
        foreach ($brands as $name) {
            $brandIds[$name] = DB::table('brands')->insertGetId([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Colors
        $colorIds = [];
        $colors = [
            'Čierna'   => '#000000',
            'Biela'    => '#FFFFFF',
            'Červená'  => '#FF0000',
            'Modrá'    => '#0000FF',
            'Zelená'   => '#008000',
            'Béžová'   => '#F5F5DC',
            'Šedá'     => '#808080',
            'Ružová'   => '#FFC0CB',
            'Hnedá'    => '#8B4513',
            'Žltá'     => '#FFD700',
        ];
        foreach ($colors as $name => $hex) {
            $colorIds[$name] = DB::table('colors')->insertGetId([
                'name' => $name,
                'hex_code' => $hex,
            ]);
        }

        // Materials
        $materialIds = [];
        $materials = ['Bavlna', 'Polyester', 'Vlna', 'Hodváb', 'Denim', 'Koža', 'Viskóza', 'Ľan'];
        foreach ($materials as $name) {
            $materialIds[$name] = DB::table('materials')->insertGetId([
                'name' => $name,
            ]);
        }

        // Products (40 total)
        $products = [
            // Oblečenie - Tričká
            ['name' => 'Biele basic tričko', 'cat' => 'Oblečenie', 'sub' => 'Tričká', 'brand' => 'H&M', 'material' => 'Bavlna', 'featured' => true],
            ['name' => 'Čierne oversize tričko', 'cat' => 'Oblečenie', 'sub' => 'Tričká', 'brand' => 'Zara', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Pruhované tričko námornícke', 'cat' => 'Oblečenie', 'sub' => 'Tričká', 'brand' => 'Mango', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Tričko s potlačou vintage', 'cat' => 'Oblečenie', 'sub' => 'Tričká', 'brand' => 'Reserved', 'material' => 'Polyester', 'featured' => false],

            // Oblečenie - Šaty
            ['name' => 'Letné kvetinové šaty', 'cat' => 'Oblečenie', 'sub' => 'Šaty', 'brand' => 'Zara', 'material' => 'Viskóza', 'featured' => true],
            ['name' => 'Mini šaty s volánmi', 'cat' => 'Oblečenie', 'sub' => 'Šaty', 'brand' => 'Mango', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Elegantné večerné šaty', 'cat' => 'Oblečenie', 'sub' => 'Šaty', 'brand' => 'Orsay', 'material' => 'Hodváb', 'featured' => true],
            ['name' => 'Maxi šaty boho štýl', 'cat' => 'Oblečenie', 'sub' => 'Šaty', 'brand' => 'Answear', 'material' => 'Ľan', 'featured' => false],
            ['name' => 'Puzdrové šaty midi', 'cat' => 'Oblečenie', 'sub' => 'Šaty', 'brand' => 'Reserved', 'material' => 'Viskóza', 'featured' => false],

            // Oblečenie - Nohavice
            ['name' => 'Skinny džínsy modré', 'cat' => 'Oblečenie', 'sub' => 'Nohavice', 'brand' => 'H&M', 'material' => 'Denim', 'featured' => false],
            ['name' => 'Wide leg nohavice béžové', 'cat' => 'Oblečenie', 'sub' => 'Nohavice', 'brand' => 'Zara', 'material' => 'Ľan', 'featured' => true],
            ['name' => 'Jogger nohavice čierne', 'cat' => 'Oblečenie', 'sub' => 'Nohavice', 'brand' => 'Reserved', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Elegantné nohavice kárované', 'cat' => 'Oblečenie', 'sub' => 'Nohavice', 'brand' => 'Mango', 'material' => 'Vlna', 'featured' => false],

            // Oblečenie - Kabáty
            ['name' => 'Vlnený kabát camel', 'cat' => 'Oblečenie', 'sub' => 'Kabáty', 'brand' => 'Mango', 'material' => 'Vlna', 'featured' => true],
            ['name' => 'Krátka bunda prešívaná', 'cat' => 'Oblečenie', 'sub' => 'Kabáty', 'brand' => 'Zara', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Trenčkot klasický béžový', 'cat' => 'Oblečenie', 'sub' => 'Kabáty', 'brand' => 'Reserved', 'material' => 'Bavlna', 'featured' => false],

            // Oblečenie - Mikiny
            ['name' => 'Oversized mikina sivá', 'cat' => 'Oblečenie', 'sub' => 'Mikiny', 'brand' => 'H&M', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Crop mikina ružová', 'cat' => 'Oblečenie', 'sub' => 'Mikiny', 'brand' => 'Answear', 'material' => 'Bavlna', 'featured' => true],
            ['name' => 'Zipová mikina čierna', 'cat' => 'Oblečenie', 'sub' => 'Mikiny', 'brand' => 'Reserved', 'material' => 'Polyester', 'featured' => false],

            // Topánky - Tenisky
            ['name' => 'Biele kožené tenisky', 'cat' => 'Topánky', 'sub' => 'Tenisky', 'brand' => 'CCC', 'material' => 'Koža', 'featured' => true],
            ['name' => 'Chunky sneakers čierne', 'cat' => 'Topánky', 'sub' => 'Tenisky', 'brand' => 'Deichmann', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Canvas tenisky farebné', 'cat' => 'Topánky', 'sub' => 'Tenisky', 'brand' => 'CCC', 'material' => 'Bavlna', 'featured' => false],

            // Topánky - Lodičky
            ['name' => 'Stiletto lodičky čierne', 'cat' => 'Topánky', 'sub' => 'Lodičky', 'brand' => 'Deichmann', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Nude lodičky kitten heel', 'cat' => 'Topánky', 'sub' => 'Lodičky', 'brand' => 'CCC', 'material' => 'Koža', 'featured' => true],
            ['name' => 'Lodičky so šnurovaním', 'cat' => 'Topánky', 'sub' => 'Lodičky', 'brand' => 'Answear', 'material' => 'Koža', 'featured' => false],

            // Topánky - Čižmy
            ['name' => 'Vysoké čižmy nad koleno', 'cat' => 'Topánky', 'sub' => 'Čižmy', 'brand' => 'CCC', 'material' => 'Koža', 'featured' => true],
            ['name' => 'Chelsea boots hnedé', 'cat' => 'Topánky', 'sub' => 'Čižmy', 'brand' => 'Deichmann', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Kotníkové čižmy s prackou', 'cat' => 'Topánky', 'sub' => 'Čižmy', 'brand' => 'Answear', 'material' => 'Koža', 'featured' => false],

            // Topánky - Sandále
            ['name' => 'Kožené sandále ploché', 'cat' => 'Topánky', 'sub' => 'Sandále', 'brand' => 'CCC', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Wedge sandále espadrilky', 'cat' => 'Topánky', 'sub' => 'Sandále', 'brand' => 'Deichmann', 'material' => 'Bavlna', 'featured' => false],

            // Doplnky - Kabelky
            ['name' => 'Crossbody kabelka mini', 'cat' => 'Doplnky', 'sub' => 'Kabelky', 'brand' => 'Zara', 'material' => 'Koža', 'featured' => true],
            ['name' => 'Tote bag plátená veľká', 'cat' => 'Doplnky', 'sub' => 'Kabelky', 'brand' => 'H&M', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Clutch zlatá večerná', 'cat' => 'Doplnky', 'sub' => 'Kabelky', 'brand' => 'Mango', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Shopper kabelka kvetinová', 'cat' => 'Doplnky', 'sub' => 'Kabelky', 'brand' => 'Reserved', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Bucket bag semišová', 'cat' => 'Doplnky', 'sub' => 'Kabelky', 'brand' => 'Answear', 'material' => 'Koža', 'featured' => true],

            // Doplnky - Opasky
            ['name' => 'Kožený opasok čierny', 'cat' => 'Doplnky', 'sub' => 'Opasky', 'brand' => 'Zara', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Elastický opasok zlatá spona', 'cat' => 'Doplnky', 'sub' => 'Opasky', 'brand' => 'Mango', 'material' => 'Polyester', 'featured' => false],

            // Doplnky - Šatky
            ['name' => 'Hodvábna šatka kvetinová', 'cat' => 'Doplnky', 'sub' => 'Šatky', 'brand' => 'Mango', 'material' => 'Hodváb', 'featured' => true],
            ['name' => 'Pletená vlnená šatka', 'cat' => 'Doplnky', 'sub' => 'Šatky', 'brand' => 'H&M', 'material' => 'Vlna', 'featured' => false],
            ['name' => 'Vzorovaná šatka vintage', 'cat' => 'Doplnky', 'sub' => 'Šatky', 'brand' => 'Reserved', 'material' => 'Viskóza', 'featured' => false],
        ];

        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $colorList = array_keys($colorIds);

        // Image placeholders per category
        $imagePaths = [
            'Oblečenie' => [
                'images/products/clothing-1.jpg',
                'images/products/clothing-2.jpg',
                'images/products/clothing-3.jpg',
            ],
            'Topánky' => [
                'images/products/shoes-1.jpg',
                'images/products/shoes-2.jpg',
                'images/products/shoes-3.jpg',
            ],
            'Doplnky' => [
                'images/products/accessories-1.jpg',
                'images/products/accessories-2.jpg',
                'images/products/accessories-3.jpg',
            ],
        ];

        foreach ($products as $index => $p) {
            $slug = Str::slug($p['name']) . '-' . ($index + 1);

            $descriptions = [
                'Kvalitný produkt z kolekcie ' . $p['brand'] . '. Vyrobený z materiálu ' . $p['material'] . '.',
                'Štýlový kúsok pre modernú ženu. Perfektný do každej príležitosti.',
                'Trendy dizajn inšpirovaný aktuálnymi módnymi trendmi zo svetových módnych týždňov.',
                'Komfortný a elegantný produkt vhodný pre každodennné nosenie.',
                'Prémiová kvalita za dostupnú cenu. Ideálny doplnok do vášho šatníka.',
            ];

            $productId = DB::table('products')->insertGetId([
                'name'           => $p['name'],
                'slug'           => $slug,
                'description'    => $descriptions[$index % count($descriptions)],
                'category_id'    => $categoryIds[$p['cat']],
                'subcategory_id' => $subcategoryIds[$p['sub']],
                'brand_id'       => $brandIds[$p['brand']],
                'material_id'    => $materialIds[$p['material']],
                'is_featured'    => $p['featured'] ? 1 : 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // 2–3 color variants per product, each with 2–4 sizes
            $numColors = rand(2, 3);
            $productColors = array_slice(array_keys($colorIds), $index % count($colorIds), $numColors);

            foreach ($productColors as $colorName) {
                $numSizes = rand(2, 4);
                $productSizes = array_slice($sizes, rand(0, count($sizes) - $numSizes), $numSizes);
                $basePrice = round(rand(1490, 9990) / 100) * 100 / 100; // e.g. 29.00

                foreach ($productSizes as $size) {
                    DB::table('product_variants')->insert([
                        'product_id'     => $productId,
                        'color_id'       => $colorIds[$colorName],
                        'size'           => $size,
                        'price'          => $basePrice,
                        'stock_quantity' => rand(0, 50),
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }
            }

            // 2–3 images per product
            $catImages = $imagePaths[$p['cat']];
            $numImages = rand(2, 3);
            for ($i = 0; $i < $numImages; $i++) {
                DB::table('product_images')->insert([
                    'product_id' => $productId,
                    'image_path' => $catImages[$i % count($catImages)],
                    'is_primary'  => $i === 0 ? 1 : 0,
                    'sort_order'  => $i,
                    'created_at'  => now(),
                ]);
            }
        }
    }
}
