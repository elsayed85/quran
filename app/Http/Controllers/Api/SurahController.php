<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Surah;
use Illuminate\Http\Request;

class SurahController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Surah::paginate(10));
    }

    public function show(Surah $surah)
    {
        return response()->json($surah->load(['verses']));
    }
}
