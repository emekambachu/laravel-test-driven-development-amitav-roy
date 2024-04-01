<?php

namespace App\Services\Video;

use App\Models\Video;
use Illuminate\Support\Facades\Auth;

class VideoService
{
    public function video(): Video
    {
        return new Video();
    }

    public function submitVideo($user, array $postData){

        $desc = $postData['description'] ?? null;

        return $this->video()->create([
            'url' => $postData['url'],
            'description' => $desc,
            'user_id' => $user->id,
            'type' => 'youtube',
            'is_published' => 0,
        ]);
    }

    public function validateYoutubeUrl($url): bool
    {
        $pattern = '/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+/';
        return preg_match($pattern, $url);
    }

    public function publishVideo($id): Video
    {
        $video = $this->video()->findOrFail($id);
        $video->is_published === 1 ? $video->is_published = 0 : $video->is_published = 1;
        $video->save();
        return $video;
    }
}
