<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class AdminVideoController extends Controller
{
    public function unpublished(): \Illuminate\Http\JsonResponse
    {
        $videos = Video::query()
            ->unpublished()
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($videos);
    }
}
