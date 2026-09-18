<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Content;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the public home page of Banca Santa Rita.
     */
    public function index(): View
    {
        $config = Configuration::current();

        // Conteúdo em Destaque Principal para o Hero
        $heroContent = Content::published()
            ->featured()
            ->with('category')
            ->ordered()
            ->first();

        // Carrossel de Destaques
        $destaques = Content::published()
            ->featured()
            ->with('category')
            ->ordered()
            ->get();

        // Seção de Fotos Recentes
        $fotos = Content::published()
            ->photos()
            ->with('category')
            ->ordered()
            ->take(6)
            ->get();

        // Seção de Vídeos Recentes
        $videos = Content::published()
            ->videos()
            ->with('category')
            ->ordered()
            ->take(4)
            ->get();

        // Seção de Itens em Exposição (Catálogo)
        $itens = Product::published()
            ->with('category')
            ->ordered()
            ->take(8)
            ->get();

        return view('site.home', compact('config', 'heroContent', 'destaques', 'fotos', 'videos', 'itens'));
    }
}
