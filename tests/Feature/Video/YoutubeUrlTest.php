<?php

namespace Tests\Feature\Video;

use App\Services\Video\VideoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class YoutubeUrlTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_validates_correct_youtube_urls(): void
    {
       $videoService = new VideoService();
       $urls = [
              'https://www.youtube.com/watch?v=GjkQNAZbxKY',
              'https://www.youtu.be/GjkQNAZbxKY',
              'https://www.youtube.com/watch?v=JGwWNGJdvx8&feature=youtu.be',
       ];

       foreach($urls as $url) {
           $this->assertEquals(1, $videoService->validateYoutubeUrl($url));
       }
    }

    public function test_invalidates_incorrect_youtube_urls(): void
    {
       $videoService = new VideoService();
       $urls = [
              'https://www.youtube.com',
       ];

       foreach($urls as $url) {
           $this->assertEquals(0, $videoService->validateYoutubeUrl($url));
       }
    }
}
