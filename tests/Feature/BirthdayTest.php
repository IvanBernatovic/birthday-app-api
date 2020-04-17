<?php

namespace Tests\Feature;

use App\Models\Birthday;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AttachJwtToken;
use Tests\RefreshAndSeedDatabase;
use Tests\TestCase;

class BirthdayTest extends TestCase
{
    use RefreshAndSeedDatabase, AttachJwtToken, WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserCanGetBirthdays()
    {
        $user = factory(User::class)->create();
        factory(Birthday::class, rand(1, 10))->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->get('api/birthdays')
            ->assertStatus(200);
    }

    public function testBirthdayBelongsToUser()
    {
        $birthday = factory(Birthday::class)->create();
        $this->assertInstanceOf(User::class, $birthday->user);
    }

    public function testUserCanAddBirthdays()
    {
        $user = factory(User::class)->create();

        $birthdayAttributes = factory(Birthday::class)->make(['user_id' => $user->id])->toArray();

        $this->actingAs($user)
            ->post('api/birthdays', $birthdayAttributes)
            ->assertStatus(200)
            ->assertJsonPath('name', $birthdayAttributes['name']);
    }

    public function testUserCanUpdateBirthday()
    {
        $birthday = factory(Birthday::class)->create();

        $this->actingAs($birthday->user)
            ->put(route('birthdays.update', ['birthday' => $birthday], false), [
                'name' => $newName = $this->faker->name,
                'date' => $newDate = $this->faker->date,
            ])
            ->assertStatus(200)
            ->assertJsonPath('name', $newName);
    }

    public function testUserCanDeleteBirthday()
    {
        $birthday = factory(Birthday::class)->create();

        $this->actingAs($birthday->user)
            ->delete(route('birthdays.delete', ['birthday' => $birthday], false))
            ->assertStatus(200);
    }
}
