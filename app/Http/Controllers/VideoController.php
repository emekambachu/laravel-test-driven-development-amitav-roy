<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Services\Video\VideoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function __construct(VideoService $video)
    {
        $this->video = $video;
    }

    public function index()
    {
        $videos = Video::query()->published()
            ->orderByDesc('created_at')
            ->paginate(10);

        return response($videos, 200);
    }

    public function store(Request $request)
    {
        $postData = $this->validate($request, [
            'url' => ['required', 'url'],
            'description' => ['sometimes'],
        ]);

        $video = $this->video->submitVideo(Auth::user(), $postData);

        return response($video, 201);
    }
}
