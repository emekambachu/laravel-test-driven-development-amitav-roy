<?php

namespace Tests\Feature\Video;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CreateVideoTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     */
    public function test_create_new_video(): void
    {
        // create data with faker
        $url = $this->faker->url();
        $description = $this->faker->sentence();

        // send request
        $response = $this->postJson('/api/video/add', [
            'url' => $url,
            'description' => $description,
        ]);

        // check if data is in the database
        $this->assertDatabaseHas('videos', [
            'url' => $url,
            'description' => $description,
            'user_id' => 1,
            'type' => 'youtube',
            'is_published' => 0,
        ]);

        // check if the response is correct
        $response->assertStatus(201);
    }

    public function test_return_video_in_response(): void
    {
        // create data with faker
        $url = $this->faker->url();
        $description = $this->faker->sentence();

        // send request
        $response = $this->postJson('/api/video/add', [
            'url' => $url,
            'description' => $description,
        ]);

        // check if the response is correct
        $response->assertJson(function (AssertableJson $json) use ($url, $description) {
            $json->where('url', $url)
                ->where('description', $description)
                ->where('id', 1)
                ->where('type', 'youtube')
                ->etc();
        });
    }

    /**
     * @throws \JsonException
     */
    public function test_return_unpublished_video(): void
    {
        // create data with faker
        $url = $this->faker->url();
        $description = $this->faker->sentence();

        // send request
        $response = $this->postJson('/api/video/add', [
            'url' => $url,
            'description' => $description,
        ]);

        // test return statement
//        dd(json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR));

        // check if the response is correct
        $response->assertJson(function (AssertableJson $json) {
            $json->where('is_published', 0)->etc();
        });
    }

    public function test_add_description_if_sent(): void
    {
        // create data with faker
        $url = $this->faker->url();

        // send request
        $response = $this->postJson('/api/video/add', [
            'url' => $url,
            'description' => 'test_description',
        ]);

        // check if the response is correct
        $response->assertJson(function (AssertableJson $json) {
            $json->where('description', 'test_description')
                ->etc();
        });
    }

    public function test_validate_required_fields(): void
    {
        // send request
        $response = $this->postJson('/api/video/add', []);

        // check if the response is correct
        $response->assertStatus(422);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('errors.url.0', 'The url field is required.')
                ->has('errors.url')
                ->etc();
        });
    }

}
