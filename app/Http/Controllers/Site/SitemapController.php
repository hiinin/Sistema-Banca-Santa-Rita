<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML sitemap for SEO indexing.
     */
    public function index(): Response
    {
        $contents = Content::published()->ordered()->get();
        $products = Product::published()->ordered()->get();

        $content = view('site.sitemap', compact('contents', 'products'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
