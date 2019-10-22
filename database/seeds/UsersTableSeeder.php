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
        \DB::table('users')->insert([
            'name' => 'Ivan Bernatović',
            'email' => 'ivan.bernatovic.93@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now()->toDateTimeString(),
            'remember_token' => Str::random(10)
        ]);
    }
}
