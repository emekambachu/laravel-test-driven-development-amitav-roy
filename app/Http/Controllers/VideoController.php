<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        return view('video');
    }

    public function store(Request $request)
    {
        $postData = $this->validate($request, [
            'url' => ['required', 'url'],
            'description' => ['sometimes'],
        ]);

        $desc = $request->has('description')
            ? $request->input('description')
            : '';

        $video = Video::create([
            'url' => $postData['url'],
            'description' => $desc,
            'user_id' => 1,
            'type' => 'youtube',
            'is_published' => 0,
        ]);

        return response($video, 201);
    }
}
