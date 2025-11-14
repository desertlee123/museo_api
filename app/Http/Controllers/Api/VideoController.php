<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::all();

        // Generar URL completa del archivo MP4
        $videos->transform(function ($v) {
            $v->url = asset('videos/' . $v->archivo);
            return $v;
        });

        return response()->json($videos, 200);
    }
}
