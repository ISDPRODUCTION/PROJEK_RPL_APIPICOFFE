<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin / Manager
        User::create([
            'name'             => 'Lionel Jevon Chrismana Putra',
            'email'            => 'etmin@apipi.com',
            'password'         => Hash::make('password'),
            'role'             => 'manager',
            'status'           => 'active',
            'employee_id'      => 'EMP-001',
            'shift_started_at' => Carbon::now()->setHour(8)->setMinute(0),
        ]);

        // Cashier
        User::create([
            'name'             => 'Sarah M.',
            'email'            => 'sarahmardianto123@apipicoffee.com',
            'password'         => Hash::make('password'),
            'role'             => 'cashier',
            'status'           => 'active',
            'employee_id'      => 'EMP-002',
            'shift_started_at' => Carbon::now()->setHour(8)->setMinute(0),
        ]);

        // Products
        $products = [
        //     ['name' => 'Caramel Macchiato',  'category' => 'drinks',  'price' => 12000, 'stock' => 50],
        //     ['name' => 'Chocolate Croissant', 'category' => 'snacks',  'price' => 13000, 'stock' => 30],
        //     ['name' => 'Cold Brew',           'category' => 'drinks',  'price' => 10000, 'stock' => 45],
        //     ['name' => 'Cappuccino',          'category' => 'drinks',  'price' => 12000, 'stock' => 40],
        //     ['name' => 'Blueberry Muffin',    'category' => 'dessert', 'price' => 8000,  'stock' => 25],
        //     ['name' => 'Iced Latte',          'category' => 'drinks',  'price' => 8000,  'stock' => 60],
        //     ['name' => 'Matcha Latte',        'category' => 'drinks',  'price' => 13000, 'stock' => 35],
        //     ['name' => 'Double Espresso',     'category' => 'drinks',  'price' => 13000, 'stock' => 55],
        //     ['name' => 'Americano',           'category' => 'drinks',  'price' => 12000, 'stock' => 45],
        //     ['name' => 'Glazed Donut',        'category' => 'dessert', 'price' => 8000,  'stock' => 20],
        //     ['name' => 'Es Kopi Susu Aren',   'category' => 'drinks',  'price' => 25000, 'stock' => 45],
        //     ['name' => 'Butter Croissant',    'category' => 'snacks',  'price' => 20000, 'stock' => 12],
        //     ['name' => 'Red Velvet Cake',     'category' => 'dessert', 'price' => 35000, 'stock' => 8],
        //     ['name' => 'Mie Goreng Apipi',    'category' => 'food',    'price' => 30000, 'stock' => 20],
         ];

        foreach ($products as $i => $data) {
            $data['sku'] = 'MNU-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
            Product::create($data);
        }
    }
}
