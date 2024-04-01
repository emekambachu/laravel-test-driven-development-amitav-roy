<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Services\Video\VideoService;
use Illuminate\Http\Request;

class AdminVideoController extends Controller
{
    private VideoService $video;
    public function __construct(VideoService $video)
    {
        $this->video = $video;
    }

    public function unpublished(): \Illuminate\Http\JsonResponse
    {
        $videos = Video::query()
            ->unpublished()
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($videos);
    }

    public function publish(Request $request): \Illuminate\Http\JsonResponse
    {
        $postData = $request->validate([
            'id' => 'required|exists:videos,id'
        ]);

        $video = $this->video->publishVideo($postData['id']);
        return response()->json($video, 201);
    }
}
