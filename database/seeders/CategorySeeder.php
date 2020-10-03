<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'category' => 'Rice',
            'icon' => 'categories/category-rice.png',
            'description' => 'Any food with rice',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Chicken',
            'icon' => 'categories/category-chicken.png',
            'description' => 'Chicken & duck',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Meat',
            'icon' => 'categories/category-meat.png',
            'description' => 'Meat including steak and barbeque',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Fish',
            'icon' => 'categories/category-fish.png',
            'description' => 'Fish tuna and salmon',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Salad',
            'icon' => 'categories/category-salad.png',
            'description' => 'Vegetable, fruit and salad',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Coffee',
            'icon' => 'categories/category-coffee.png',
            'description' => 'Coffee and hot drink',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Beverage',
            'icon' => 'categories/category-beverage.png',
            'description' => 'Kind of beverages',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Snack',
            'icon' => 'categories/category-snack.png',
            'description' => 'Snack and appetizer',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Noodles',
            'icon' => 'categories/category-noodles.png',
            'description' => 'Noodles and spaghetti',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Fast Food',
            'icon' => 'categories/category-burger.png',
            'description' => 'Burger and fast food',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Ice Cream',
            'icon' => 'categories/category-ice-cream.png',
            'description' => 'Ice cream, freeze drink',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Pizza',
            'icon' => 'categories/category-pizza.png',
            'description' => 'Pizza, so italiano',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Sandwich',
            'icon' => 'categories/category-sandwich.png',
            'description' => 'Sandwich and bread',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Soup',
            'icon' => 'categories/category-soup.png',
            'description' => 'Soup, yummy',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Bread',
            'icon' => 'categories/category-bread.png',
            'description' => 'Dry bread',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Cookie',
            'icon' => 'categories/category-cookie.png',
            'description' => 'Cookie and biscuit',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Japanese',
            'icon' => 'categories/category-japanese.png',
            'description' => 'Korean, chinese and japanese food',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Sausage',
            'icon' => 'categories/category-sausage.png',
            'description' => 'Snack sausage',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
