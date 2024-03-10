<?php

namespace Tests\Feature\Video;

use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class VideoListTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_get_video_list(): void
    {
        Video::factory()->count(10)->create();
        $response = $this->getJson('/api/video');

        // test return statement
        // dd(json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR));

        $response->assertJson(function (AssertableJson $json) {
            $json->where('total', 10)
                ->where('per_page', 10)
                ->has('data', 10)
                ->etc();
        });
    }

    public function test_shows_first_n_videos(): void
    {
        Video::factory()->count(15)->create();
        $response = $this->getJson('/api/video');

        $response->assertJson(function (AssertableJson $json) {
            $json->where('total', 15)
                ->has('data', 10)
                ->has('data.0', function ($video) {
                    $video->where('is_published', 1)->etc();
                })
                ->etc();
        });
    }

    public function test_show_only_published_videos(): void
    {
        Video::factory()->count(10)->create();
        Video::factory()->count(5)->unpublished()->create();

        $response = $this->getJson('/api/video');

        $response->assertJson(function (AssertableJson $json) {
            $json->where('total', 10)
                ->has('data', 10)
                ->has('data.0', function ($video) {
                    $video->where('is_published', 1)->etc();
                })
                ->etc();
        });
    }
}
