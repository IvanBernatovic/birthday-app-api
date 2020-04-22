<?php

use App\Models\Birthday;
use App\Models\Gift;
use App\User;
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
        User::create([
            'name' => 'Ivan Bernatović',
            'email' => 'ivan.bernatovic.93@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now()->toDateTimeString(),
            'remember_token' => Str::random(10),
        ]);

        factory(User::class, 5)->create();

        foreach (User::all() as $user) {
            factory(Birthday::class, random_int(4, 16))->create([
                'user_id' => $user->id,
            ]);

            $user->birthdays->each(function ($birthday) {
                factory(Gift::class, random_int(0, 4))->create([
                    'birthday_id' => $birthday->id,
                ]);
            });
        }
    }
}
