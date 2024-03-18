<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Rules\YoutubeUrlRule;
use App\Services\Video\VideoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

    /**
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $postData = $this->validate($request, [
            'url' => ['required', 'url', new YoutubeUrlRule()],
            'description' => ['sometimes'],
        ]);

        $video = $this->video->submitVideo(Auth::user(), $postData);

        return response($video, 201);
    }
}
