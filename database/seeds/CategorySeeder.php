<?php

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
            'icon' => '/storage/categories/category-rice.png',
            'description' => 'Any food with rice',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Chicken',
            'icon' => '/storage/categories/category-chicken.png',
            'description' => 'Chicken & duck',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Meat',
            'icon' => '/storage/categories/category-meat.png',
            'description' => 'Meat including steak and barbeque',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Fish',
            'icon' => '/storage/categories/category-fish.png',
            'description' => 'Fish tuna and salmon',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Salad',
            'icon' => '/storage/categories/category-salad.png',
            'description' => 'Vegetable, fruit and salad',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Coffee',
            'icon' => '/storage/categories/category-coffee.png',
            'description' => 'Coffee and hot drink',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Beverage',
            'icon' => '/storage/categories/category-beverage.png',
            'description' => 'Kind of beverages',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Snack',
            'icon' => '/storage/categories/category-snack.png',
            'description' => 'Snack and appetizer',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Noodles',
            'icon' => '/storage/categories/category-noodles.png',
            'description' => 'Noodles and spaghetti',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Fast Food',
            'icon' => '/storage/categories/category-burger.png',
            'description' => 'Burger and fast food',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Ice Cream',
            'icon' => '/storage/categories/category-ice-cream.png',
            'description' => 'Ice cream, freeze drink',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Pizza',
            'icon' => '/storage/categories/category-pizza.png',
            'description' => 'Pizza, so italiano',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Sandwich',
            'icon' => '/storage/categories/category-sandwich.png',
            'description' => 'Sandwich and bread',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Soup',
            'icon' => '/storage/categories/category-soup.png',
            'description' => 'Soup, yummy',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Bread',
            'icon' => '/storage/categories/category-bread.png',
            'description' => 'Dry bread',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Cookie',
            'icon' => '/storage/categories/category-cookie.png',
            'description' => 'Cookie and biscuit',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Japanese',
            'icon' => '/storage/categories/category-japanese.png',
            'description' => 'Korean, chinese and japanese food',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('categories')->insert([
            'category' => 'Sausage',
            'icon' => '/storage/categories/category-sausage.png',
            'description' => 'Snack sausage',
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
