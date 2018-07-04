<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = Hash::make('test');

        User::create([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'password' => $password,
            'phone_number' => '777777777777'
        ]);
    }
}
