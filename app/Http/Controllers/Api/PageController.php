<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Page $page)
    {
        $page->load(['startSurah', 'endSurah']);
        return response()->json([
            'page' => $page,
            'verses' => $page->verses()->get()
        ]);
    }
}
