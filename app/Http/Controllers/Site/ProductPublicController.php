<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Configuration;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductPublicController extends Controller
{
    /**
     * Display a listing of published exhibition items with category filter.
     */
    public function index(Request $request): View
    {
        $query = Product::published()->with('category');

        if ($categoriaId = $request->input('categoria_id')) {
            $query->where('categoria_id', $categoriaId);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        $itens = $query->ordered()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::active()->ordered()->get();
        $config = Configuration::current();

        return view('site.products.index', compact('itens', 'categories', 'config'));
    }

    /**
     * Display the specified exhibition item with WhatsApp inquiry button.
     */
    public function show(string $slug): View
    {
        $item = Product::published()
            ->with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        $relacionados = Product::published()
            ->where('categoria_id', $item->categoria_id)
            ->where('id', '!=', $item->id)
            ->ordered()
            ->take(4)
            ->get();

        $config = Configuration::current();

        // Gerador de mensagem amigável para consulta via WhatsApp
        $whatsappMsg = "Olá! Vi o item '{$item->nome}' no catálogo do site da Banca Santa Rita e gostaria de consultar a disponibilidade.";
        $whatsappUrl = $config->getWhatsappUrl($whatsappMsg);

        return view('site.products.show', compact('item', 'relacionados', 'config', 'whatsappUrl'));
    }
}
