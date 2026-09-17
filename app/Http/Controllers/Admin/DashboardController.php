<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Configuration;
use App\Models\Content;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the administrative dashboard.
     */
    public function index(): View
    {
        $metrics = [
            'totalConteudos' => Content::count(),
            'conteudosPublicados' => Content::where('status', 'publicado')->count(),
            'conteudosRascunho' => Content::where('status', 'rascunho')->count(),
            'totalFotos' => Content::where('tipo', 'foto')->count(),
            'totalVideos' => Content::where('tipo', 'video')->count(),
            'totalItens' => Product::count(),
            'totalCategorias' => Category::count(),
        ];

        $ultimosConteudos = Content::with('category')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        $ultimosItens = Product::with('category')
            ->orderByDesc('id')
            ->take(4)
            ->get();

        $bancaConfig = Configuration::current();

        return view('admin.dashboard', compact('metrics', 'ultimosConteudos', 'ultimosItens', 'bancaConfig'));
    }
}
