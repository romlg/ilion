<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Pety',
            'middle_name' => 'Petrovich',
            'last_name' => 'Petrov',
            'email' => 'pety@gmail.com',
            'password' => bcrypt('11111111'),
            'is_active' => 1,
            'is_admin' => 1,
            'phone' => '11-11-111-111',
            'post' => 'Director',
        ]);
    }
}
