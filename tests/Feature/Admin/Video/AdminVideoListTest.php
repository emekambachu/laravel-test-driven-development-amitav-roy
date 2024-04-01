<?php

namespace Admin\Video;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Validation\UnauthorizedException;
use Tests\TestCase;

class AdminVideoListTest extends TestCase
{
    use RefreshDatabase;

    public function test_allows_admin_only(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/admin/video/list/unpublished')
            ->assertStatus(401);
    }

    public function test_shows_unpublished_videos(): void
    {
        Video::factory()->unpublished()->count(2)->create();
        Video::factory()->count(5)->create();

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->getJson('/api/admin/video/list/unpublished')
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', 2)
                    ->where('data.0.is_published', 0)
                    ->etc();
            });
    }
}
