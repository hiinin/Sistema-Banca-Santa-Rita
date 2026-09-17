<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catJornais = Category::where('slug', 'jornais-do-dia')->first() ?? Category::first();
        $catRevistas = Category::where('slug', 'revistas-periodicos')->first() ?? Category::first();
        $catQuadrinhos = Category::where('slug', 'quadrinhos-mangas')->first() ?? Category::first();
        $catLivros = Category::where('slug', 'livros-best-sellers')->first() ?? Category::first();
        $catColecionaveis = Category::where('slug', 'colecionaveis-albuns')->first() ?? Category::first();
        $catPapelaria = Category::where('slug', 'papelaria-conveniencia')->first() ?? Category::first();

        $items = [
            [
                'nome' => 'Jornal Folha de S.Paulo - Edição Completa',
                'slug' => 'jornal-folha-de-s-paulo',
                'descricao' => 'Edição impressa matinal com cadernos de Cotidiano, Opinião, Ilustrada, Mercado e Esporte.',
                'imagem' => 'images/demo-jornais-manhas.svg',
                'preco' => 9.00,
                'categoria_id' => $catJornais->id,
                'destaque' => 'sim',
                'status' => 'publicado',
                'ordem' => 1,
            ],
            [
                'nome' => 'Revista Superinteressante - Edição do Mês',
                'slug' => 'revista-superinteressante',
                'descricao' => 'Dossiês profundos sobre ciência, história, comportamento, futuro e mistérios do universo.',
                'imagem' => 'images/demo-revistas-semanais.svg',
                'preco' => 24.90,
                'categoria_id' => $catRevistas->id,
                'destaque' => 'sim',
                'status' => 'publicado',
                'ordem' => 2,
            ],
            [
                'nome' => 'Revista Piauí - Edição Mensal',
                'slug' => 'revista-piaui',
                'descricao' => 'Jornalismo narrativo aprofundado, reportagens investigativas, ensaios e humor refinado.',
                'imagem' => 'images/demo-revistas-semanais.svg',
                'preco' => 32.00,
                'categoria_id' => $catRevistas->id,
                'destaque' => 'não',
                'status' => 'publicado',
                'ordem' => 3,
            ],
            [
                'nome' => 'Graphic Novel Clássicos dos Quadrinhos',
                'slug' => 'graphic-novel-classicos-quadrinhos',
                'descricao' => 'Edição de colecionador em capa dura com ilustrações exclusivas e acabamento premium.',
                'imagem' => 'images/demo-colecionaveis.svg',
                'preco' => 59.90,
                'categoria_id' => $catQuadrinhos->id,
                'destaque' => 'sim',
                'status' => 'publicado',
                'ordem' => 4,
            ],
            [
                'nome' => 'Gibi Turma da Mônica Edição Histórica',
                'slug' => 'gibi-turma-da-monica-historica',
                'descricao' => 'Revista em quadrinhos com histórias clássicas dos personagens mais queridos do Brasil.',
                'imagem' => 'images/demo-colecionaveis.svg',
                'preco' => 12.50,
                'categoria_id' => $catQuadrinhos->id,
                'destaque' => 'não',
                'status' => 'publicado',
                'ordem' => 5,
            ],
            [
                'nome' => 'Álbum de Figurinhas Oficial + Pacotes',
                'slug' => 'album-de-figurinhas-oficial',
                'descricao' => 'Álbum ilustrado oficial para completar sua coleção. Pacotes com cromos brilhantes e especiais.',
                'imagem' => 'images/demo-colecionaveis.svg',
                'preco' => 15.00,
                'categoria_id' => $catColecionaveis->id,
                'destaque' => 'sim',
                'status' => 'publicado',
                'ordem' => 6,
            ],
            [
                'nome' => 'Livro: O Poder do Hábito - Charles Duhigg',
                'slug' => 'livro-o-poder-do-habito',
                'descricao' => 'Best-seller consagrado sobre neurociência da formação de hábitos e produtividade.',
                'imagem' => 'images/demo-revistas-semanais.svg',
                'preco' => 44.90,
                'categoria_id' => $catLivros->id,
                'destaque' => 'não',
                'status' => 'publicado',
                'ordem' => 7,
            ],
            [
                'nome' => 'Kit Escrita Fineliner 10 Cores',
                'slug' => 'kit-escrita-fineliner-10-cores',
                'descricao' => 'Canetas ponta fina 0.4mm de alta precisão para anotações, desenhos e passatempos.',
                'imagem' => 'images/placeholder-item.svg',
                'preco' => 29.90,
                'categoria_id' => $catPapelaria->id,
                'destaque' => 'não',
                'status' => 'publicado',
                'ordem' => 8,
            ],
        ];

        foreach ($items as $item) {
            Product::updateOrCreate(['slug' => $item['slug']], $item);
        }
    }
}
