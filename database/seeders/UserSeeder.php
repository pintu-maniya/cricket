<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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
        $user = [
            1 =>
                [
                    'name' => 'admin',
                    'email' => 'admin@gmail.com',
                    'password' => Hash::make('12345678'),
                    'level' => 0,
                ]
        ];
        foreach($user as $key => $value){
            User::updateOrCreate(['id' => $key],$value);
        }
    }
}
