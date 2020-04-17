<?php

namespace Tests\Feature;

use App\Models\Birthday;
use App\Models\Gift;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AttachJwtToken;
use Tests\TestCase;

class BirthdayGiftTest extends TestCase
{
    use RefreshDatabase, AttachJwtToken;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserCanAddBirthdayGifts()
    {
        $birthday = factory(Birthday::class)->create();
        $gift = factory(Gift::class)->make();

        $this->actingAs($birthday->user)
            ->post(route('gifts.store', [$birthday], false), [
                'body' => $gift->body,
            ])
            ->assertStatus(200);
    }

    public function testUserCanDeleteBirthdayGifts()
    {
        $gift = factory(Gift::class)->create();

        $this->actingAs($gift->birthday->user)
            ->delete(route('gifts.delete', [$gift->birthday, $gift], false))
            ->assertStatus(200);

    }
}
