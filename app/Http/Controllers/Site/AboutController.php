<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Content;
use Illuminate\View\View;

class AboutController extends Controller
{
    /**
     * Display the About page of Banca Santa Rita.
     */
    public function index(): View
    {
        $config = Configuration::current();

        $fotosBanca = Content::published()
            ->photos()
            ->ordered()
            ->take(4)
            ->get();

        return view('site.about', compact('config', 'fotosBanca'));
    }
}
