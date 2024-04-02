<?php

namespace Tests\Feature\Video;

use App\Mail\VideoPublishedEmailToOwner;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class VideoPublishedTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_publishes_an_unpublished_video(): void
    {
       $admin = User::factory()->admin()->create();
       $video = Video::factory()->unpublished()->create();

       $this->actingAs($admin)
           ->json('POST', '/api/admin/video/publish',
               [
                   'id' => $video->id
               ])
            ->assertStatus(201)
            ->assertJson(function (AssertableJson $json) use ($video) {
                $json->where('is_published', 1)
                    ->where('id', $video->id)
                    ->etc();
            });
    }

    public function test_sends_an_email_to_owner_when_published(): void
    {
        Mail::fake();

        $admin = User::factory()->admin()->create();
        $user = Video::factory()->create();

        $video = Video::factory()->unpublished()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($admin)
            ->json('POST', '/api/admin/video/publish',
                [
                    'id' => $video->id
                ]);

        $this->assertDatabaseHas('videos', [
            'id' => $video->id,
            'is_published' => 1
        ]);

        Mail::assertQueued(VideoPublishedEmailToOwner::class, static function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

    }
}
