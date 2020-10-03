<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Angga Ari',
            'email' => 'anggadarkprince@gmail.com',
            'password' => Hash::make('anggaari'),
            'type' => User::TYPE_MANAGEMENT,
        ]);

        DB::table('users')->insert([
            'name' => 'Ari Wijaya',
            'email' => 'angga.aw92@gmail.com',
            'password' => Hash::make('anggaari'),
            'type' => User::TYPE_CUSTOMER,
            'balance' => 9900000
        ]);
    }
}
