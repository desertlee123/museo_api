<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::select('id', 'titulo', 'youtube_url')->get();

        return response()->json($videos, 200);
    }
}
