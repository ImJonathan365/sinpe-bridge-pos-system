<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'code' => 'PROD001',
            'name' => 'Laptop Dell XPS 13',
            'price' => 1299.99,
            'active' => true,
        ]);

        Product::create([
            'code' => 'PROD002',
            'name' => 'Mouse Logitech MX Master 3',
            'price' => 99.99,
            'active' => true,
        ]);

        Product::create([
            'code' => 'PROD003',
            'name' => 'Teclado Mecánico Corsair K95',
            'price' => 199.99,
            'active' => true,
        ]);

        Product::create([
            'code' => 'PROD004',
            'name' => 'Monitor LG UltraWide',
            'price' => 499.99,
            'active' => true,
        ]);

        Product::create([
            'code' => 'PROD005',
            'name' => 'Headphones Sony WH-1000XM5',
            'price' => 399.99,
            'active' => true,
        ]);

        Product::create([
            'code' => 'PROD006',
            'name' => 'Webcam Logitech 4K',
            'price' => 149.99,
            'active' => true,
        ]);

        Product::create([
            'code' => 'PROD007',
            'name' => 'SSD Samsung 980 Pro',
            'price' => 249.99,
            'active' => true,
        ]);

        Product::create([
            'code' => 'PROD008',
            'name' => 'Tablet iPad Pro',
            'price' => 1099.99,
            'active' => true,
        ]);

        Product::create([
            'code' => 'PROD009',
            'name' => 'Cable HDMI 2.1',
            'price' => 24.99,
            'active' => true,
        ]);

        Product::create([
            'code' => 'PROD010',
            'name' => 'Router WiFi 6 ASUS',
            'price' => 299.99,
            'active' => true,
        ]);

        Product::create([
            'code' => 'PROD011',
            'name' => 'Batería Externa 20000mAh',
            'price' => 49.99,
            'active' => true,
        ]);

        Product::create([
            'code' => 'PROD012',
            'name' => 'Cargador Rápido 65W',
            'price' => 79.99,
            'active' => true,
        ]);
    }
}
