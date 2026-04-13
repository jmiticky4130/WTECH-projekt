<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = [];
        $categories = ['Oblečenie', 'Topánky', 'Doplnky'];
        foreach ($categories as $name) {
            $categoryIds[$name] = DB::table('categories')->insertGetId([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
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
        $materialIds = [];
        $materials = ['Bavlna', 'Polyester', 'Vlna', 'Hodváb', 'Denim', 'Koža', 'Viskóza', 'Ľan'];
        foreach ($materials as $name) {
            $materialIds[$name] = DB::table('materials')->insertGetId([
                'name' => $name,
            ]);
        }
        $products = [
            ['name' => 'Biele basic tričko', 'cat' => 'Oblečenie', 'sub' => 'Tričká', 'brand' => 'H&M', 'material' => 'Bavlna', 'featured' => true],
            ['name' => 'Čierne oversize tričko', 'cat' => 'Oblečenie', 'sub' => 'Tričká', 'brand' => 'Zara', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Pruhované tričko námornícke', 'cat' => 'Oblečenie', 'sub' => 'Tričká', 'brand' => 'Mango', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Tričko s potlačou vintage', 'cat' => 'Oblečenie', 'sub' => 'Tričká', 'brand' => 'Reserved', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Letné kvetinové šaty', 'cat' => 'Oblečenie', 'sub' => 'Šaty', 'brand' => 'Zara', 'material' => 'Viskóza', 'featured' => true],
            ['name' => 'Mini šaty s volánmi', 'cat' => 'Oblečenie', 'sub' => 'Šaty', 'brand' => 'Mango', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Elegantné večerné šaty', 'cat' => 'Oblečenie', 'sub' => 'Šaty', 'brand' => 'Orsay', 'material' => 'Hodváb', 'featured' => true],
            ['name' => 'Maxi šaty boho štýl', 'cat' => 'Oblečenie', 'sub' => 'Šaty', 'brand' => 'Answear', 'material' => 'Ľan', 'featured' => false],
            ['name' => 'Puzdrové šaty midi', 'cat' => 'Oblečenie', 'sub' => 'Šaty', 'brand' => 'Reserved', 'material' => 'Viskóza', 'featured' => false],
            ['name' => 'Skinny džínsy modré', 'cat' => 'Oblečenie', 'sub' => 'Nohavice', 'brand' => 'H&M', 'material' => 'Denim', 'featured' => false],
            ['name' => 'Wide leg nohavice béžové', 'cat' => 'Oblečenie', 'sub' => 'Nohavice', 'brand' => 'Zara', 'material' => 'Ľan', 'featured' => true],
            ['name' => 'Jogger nohavice čierne', 'cat' => 'Oblečenie', 'sub' => 'Nohavice', 'brand' => 'Reserved', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Elegantné nohavice kárované', 'cat' => 'Oblečenie', 'sub' => 'Nohavice', 'brand' => 'Mango', 'material' => 'Vlna', 'featured' => false],
            ['name' => 'Vlnený kabát camel', 'cat' => 'Oblečenie', 'sub' => 'Kabáty', 'brand' => 'Mango', 'material' => 'Vlna', 'featured' => true],
            ['name' => 'Krátka bunda prešívaná', 'cat' => 'Oblečenie', 'sub' => 'Kabáty', 'brand' => 'Zara', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Trenčkot klasický béžový', 'cat' => 'Oblečenie', 'sub' => 'Kabáty', 'brand' => 'Reserved', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Oversized mikina sivá', 'cat' => 'Oblečenie', 'sub' => 'Mikiny', 'brand' => 'H&M', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Crop mikina ružová', 'cat' => 'Oblečenie', 'sub' => 'Mikiny', 'brand' => 'Answear', 'material' => 'Bavlna', 'featured' => true],
            ['name' => 'Zipová mikina čierna', 'cat' => 'Oblečenie', 'sub' => 'Mikiny', 'brand' => 'Reserved', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Biele kožené tenisky', 'cat' => 'Topánky', 'sub' => 'Tenisky', 'brand' => 'CCC', 'material' => 'Koža', 'featured' => true],
            ['name' => 'Chunky sneakers čierne', 'cat' => 'Topánky', 'sub' => 'Tenisky', 'brand' => 'Deichmann', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Canvas tenisky farebné', 'cat' => 'Topánky', 'sub' => 'Tenisky', 'brand' => 'CCC', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Stiletto lodičky čierne', 'cat' => 'Topánky', 'sub' => 'Lodičky', 'brand' => 'Deichmann', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Nude lodičky kitten heel', 'cat' => 'Topánky', 'sub' => 'Lodičky', 'brand' => 'CCC', 'material' => 'Koža', 'featured' => true],
            ['name' => 'Lodičky so šnurovaním', 'cat' => 'Topánky', 'sub' => 'Lodičky', 'brand' => 'Answear', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Vysoké čižmy nad koleno', 'cat' => 'Topánky', 'sub' => 'Čižmy', 'brand' => 'CCC', 'material' => 'Koža', 'featured' => true],
            ['name' => 'Chelsea boots hnedé', 'cat' => 'Topánky', 'sub' => 'Čižmy', 'brand' => 'Deichmann', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Kotníkové čižmy s prackou', 'cat' => 'Topánky', 'sub' => 'Čižmy', 'brand' => 'Answear', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Kožené sandále ploché', 'cat' => 'Topánky', 'sub' => 'Sandále', 'brand' => 'CCC', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Wedge sandále espadrilky', 'cat' => 'Topánky', 'sub' => 'Sandále', 'brand' => 'Deichmann', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Crossbody kabelka mini', 'cat' => 'Doplnky', 'sub' => 'Kabelky', 'brand' => 'Zara', 'material' => 'Koža', 'featured' => true],
            ['name' => 'Tote bag plátená veľká', 'cat' => 'Doplnky', 'sub' => 'Kabelky', 'brand' => 'H&M', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Clutch zlatá večerná', 'cat' => 'Doplnky', 'sub' => 'Kabelky', 'brand' => 'Mango', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Shopper kabelka kvetinová', 'cat' => 'Doplnky', 'sub' => 'Kabelky', 'brand' => 'Reserved', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Bucket bag semišová', 'cat' => 'Doplnky', 'sub' => 'Kabelky', 'brand' => 'Answear', 'material' => 'Koža', 'featured' => true],
            ['name' => 'Kožený opasok čierny', 'cat' => 'Doplnky', 'sub' => 'Opasky', 'brand' => 'Zara', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Elastický opasok zlatá spona', 'cat' => 'Doplnky', 'sub' => 'Opasky', 'brand' => 'Mango', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Hodvábna šatka kvetinová', 'cat' => 'Doplnky', 'sub' => 'Šatky', 'brand' => 'Mango', 'material' => 'Hodváb', 'featured' => true],
            ['name' => 'Pletená vlnená šatka', 'cat' => 'Doplnky', 'sub' => 'Šatky', 'brand' => 'H&M', 'material' => 'Vlna', 'featured' => false],
            ['name' => 'Vzorovaná šatka vintage', 'cat' => 'Doplnky', 'sub' => 'Šatky', 'brand' => 'Reserved', 'material' => 'Viskóza', 'featured' => false],
            ['name' => 'Základné bavlnené tričko', 'cat' => 'Oblečenie', 'sub' => 'Tričká', 'brand' => 'Reserved', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Tričko s dlhým rukávom', 'cat' => 'Oblečenie', 'sub' => 'Tričká', 'brand' => 'Mango', 'material' => 'Bavlna', 'featured' => true],
            ['name' => 'Športové funkčné tričko', 'cat' => 'Oblečenie', 'sub' => 'Tričká', 'brand' => 'Answear', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Polo tričko tmavomodré', 'cat' => 'Oblečenie', 'sub' => 'Tričká', 'brand' => 'Zara', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Grafické tričko s nápisom', 'cat' => 'Oblečenie', 'sub' => 'Tričká', 'brand' => 'H&M', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Pletené zimné šaty', 'cat' => 'Oblečenie', 'sub' => 'Šaty', 'brand' => 'Mango', 'material' => 'Vlna', 'featured' => true],
            ['name' => 'Košeľové šaty khaki', 'cat' => 'Oblečenie', 'sub' => 'Šaty', 'brand' => 'Reserved', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Tee šaty ležérne', 'cat' => 'Oblečenie', 'sub' => 'Šaty', 'brand' => 'H&M', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Koktejlové šaty čierne', 'cat' => 'Oblečenie', 'sub' => 'Šaty', 'brand' => 'Zara', 'material' => 'Viskóza', 'featured' => true],
            ['name' => 'Plážové šaty ľahké', 'cat' => 'Oblečenie', 'sub' => 'Šaty', 'brand' => 'Orsay', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Cargo nohavice zelené', 'cat' => 'Oblečenie', 'sub' => 'Nohavice', 'brand' => 'Zara', 'material' => 'Bavlna', 'featured' => true],
            ['name' => 'Chino nohavice svetlé', 'cat' => 'Oblečenie', 'sub' => 'Nohavice', 'brand' => 'H&M', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Zamatové nohavice', 'cat' => 'Oblečenie', 'sub' => 'Nohavice', 'brand' => 'Reserved', 'material' => 'Viskóza', 'featured' => false],
            ['name' => 'Mom jeans vintage', 'cat' => 'Oblečenie', 'sub' => 'Nohavice', 'brand' => 'Answear', 'material' => 'Denim', 'featured' => true],
            ['name' => 'Zvonové džínsy', 'cat' => 'Oblečenie', 'sub' => 'Nohavice', 'brand' => 'Mango', 'material' => 'Denim', 'featured' => false],
            ['name' => 'Dlhý zimný kabát s kapucňou', 'cat' => 'Oblečenie', 'sub' => 'Kabáty', 'brand' => 'Zara', 'material' => 'Vlna', 'featured' => false],
            ['name' => 'Parka khaki s kožušinou', 'cat' => 'Oblečenie', 'sub' => 'Kabáty', 'brand' => 'H&M', 'material' => 'Polyester', 'featured' => true],
            ['name' => 'Falošná kožená bunda', 'cat' => 'Oblečenie', 'sub' => 'Kabáty', 'brand' => 'Mango', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Riflová budna klasická', 'cat' => 'Oblečenie', 'sub' => 'Kabáty', 'brand' => 'Reserved', 'material' => 'Denim', 'featured' => false],
            ['name' => 'Mikina s kapucňou basic', 'cat' => 'Oblečenie', 'sub' => 'Mikiny', 'brand' => 'H&M', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Fleece mikina teplá', 'cat' => 'Oblečenie', 'sub' => 'Mikiny', 'brand' => 'Answear', 'material' => 'Polyester', 'featured' => true],
            ['name' => 'Dlhá mikina ako šaty', 'cat' => 'Oblečenie', 'sub' => 'Mikiny', 'brand' => 'Zara', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Slip-on tenisky čierne', 'cat' => 'Topánky', 'sub' => 'Tenisky', 'brand' => 'Deichmann', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Športové bežecké tenisky', 'cat' => 'Topánky', 'sub' => 'Tenisky', 'brand' => 'CCC', 'material' => 'Polyester', 'featured' => true],
            ['name' => 'High-top tenisky kožené', 'cat' => 'Topánky', 'sub' => 'Tenisky', 'brand' => 'Answear', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Červené lodičky lakované', 'cat' => 'Topánky', 'sub' => 'Lodičky', 'brand' => 'Deichmann', 'material' => 'Koža', 'featured' => true],
            ['name' => 'Slingback lodičky perleťové', 'cat' => 'Topánky', 'sub' => 'Lodičky', 'brand' => 'CCC', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Platformové lodičky párty', 'cat' => 'Topánky', 'sub' => 'Lodičky', 'brand' => 'Answear', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Biker boots nízke', 'cat' => 'Topánky', 'sub' => 'Čižmy', 'brand' => 'Deichmann', 'material' => 'Koža', 'featured' => true],
            ['name' => 'Zimné snehule s kožušinou', 'cat' => 'Topánky', 'sub' => 'Čižmy', 'brand' => 'CCC', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Kovbojské čižmy hnedé', 'cat' => 'Topánky', 'sub' => 'Čižmy', 'brand' => 'Answear', 'material' => 'Koža', 'featured' => true],
            ['name' => 'Platformové sandále leto', 'cat' => 'Topánky', 'sub' => 'Sandále', 'brand' => 'Deichmann', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Rímske sandále šnurovacie', 'cat' => 'Topánky', 'sub' => 'Sandále', 'brand' => 'CCC', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Šľapky k vode neonové', 'cat' => 'Topánky', 'sub' => 'Sandále', 'brand' => 'Answear', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Mestský elegantný ruksak', 'cat' => 'Doplnky', 'sub' => 'Kabelky', 'brand' => 'Zara', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Športová ľadvinka', 'cat' => 'Doplnky', 'sub' => 'Kabelky', 'brand' => 'H&M', 'material' => 'Polyester', 'featured' => false],
            ['name' => 'Listová kabelka strieborná', 'cat' => 'Doplnky', 'sub' => 'Kabelky', 'brand' => 'Mango', 'material' => 'Polyester', 'featured' => true],
            ['name' => 'Kabelka s masívnou reťazou', 'cat' => 'Doplnky', 'sub' => 'Kabelky', 'brand' => 'Reserved', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Tenký kožený opasok biely', 'cat' => 'Doplnky', 'sub' => 'Opasky', 'brand' => 'Zara', 'material' => 'Koža', 'featured' => false],
            ['name' => 'Látkový opasok s prackou', 'cat' => 'Doplnky', 'sub' => 'Opasky', 'brand' => 'H&M', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Pruhovaná letná šatka', 'cat' => 'Doplnky', 'sub' => 'Šatky', 'brand' => 'Mango', 'material' => 'Bavlna', 'featured' => false],
            ['name' => 'Teplý zimný šál s biely', 'cat' => 'Doplnky', 'sub' => 'Šatky', 'brand' => 'H&M', 'material' => 'Vlna', 'featured' => true],
        ];

        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $colorList = array_keys($colorIds);
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
                'is_featured'    => $p['featured'] ? 'true' : 'false',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
            $numColors = rand(2, 3);
            $productColors = array_slice(array_keys($colorIds), $index % count($colorIds), $numColors);

            foreach ($productColors as $colorName) {
                $numSizes = rand(2, 4);
                $productSizes = array_slice($sizes, rand(0, count($sizes) - $numSizes), $numSizes);
                $basePrice = round(rand(1490, 9990) / 100) * 100 / 100;

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
            $catImages = $imagePaths[$p['cat']];
            $numImages = rand(2, 3);
            for ($i = 0; $i < $numImages; $i++) {
                DB::table('product_images')->insert([
                    'product_id' => $productId,
                    'image_path' => $catImages[$i % count($catImages)],
                    'is_primary'  => $i === 0 ? 'true' : 'false',
                    'sort_order'  => $i,
                    'created_at'  => now(),
                ]);
            }
        }
    }
}
