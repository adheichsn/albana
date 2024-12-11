<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name'=>'Americano',
                'price'=>20000,
                'category_id'=>Category::where('name', 'Coffee')->first()->id,
                'size'=>'',
                'stok'=>5,
                'desc'=>'kopi Americano adalah espresso yang ditambahkan air panas. ',
                'img'=>'product-img/prod1.jpg',
            ],
            [
                'name'=>'Matcha',
                'price'=>27000,
                'category_id'=>Category::where('name', 'Non Coffee')->first()->id,
                'size'=>'',
                'stok'=>5,
                'desc'=>'Matcha latte adalah minuman yang terbuat dari campuran bubuk matcha yang dilarutkan dengan susu steam sehingga menghasilkan minuman yang manis dan creamy dengan sedikit rasa pahit dari teh hijau.',
                'img'=>'product-img/prod3.jpg',
            ],
            [
                'name'=>'Caramel Machiato',
                'price'=>25000,
                'category_id'=>Category::where('name', 'Coffee')->first()->id,
                'size'=>'',
                'stok'=>5,
                'desc'=>'Caramel macchiato merupakan salah satu minuman kopi khas Italia yang terdiri dari campuran satu shot espresso, susu steam, dan sirup vanila serta topping saus karamel. ',
                'img'=>'product-img/prod4.jpg',
            ],
            [
                'name'=>'Hazelnut Latte',
                'price'=>29000,
                'category_id'=>Category::where('name', 'Coffee')->first()->id,
                'size'=>'',
                'stok'=>5,
                'desc'=>'Minuman ini adalah minuman kopi yang terbuat dari kombinasi espresso, susu, dan sirup rasa hazelnut.',
                'img'=>'product-img/prod5.jpg',
            ],
            [
                'name'=>'Chcolate',
                'price'=>20000,
                'category_id'=>Category::where('name', 'Non Coffee')->first()->id,
                'size'=>'',
                'stok'=>5,
                'desc'=>'Chcolate',
                'img'=>'product-img/prod7.jpg',
            ],
        ];

        foreach($products as $product){
            Product::create($product);
        }
    }
}
