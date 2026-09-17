<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nome' => 'Destaques & Novidades',
                'slug' => 'destaques-novidades',
                'descricao' => 'Os principais lançamentos da semana e itens em evidência na Banca Santa Rita.',
                'status' => 'ativo',
                'ordem' => 1,
            ],
            [
                'nome' => 'Jornais do Dia',
                'slug' => 'jornais-do-dia',
                'descricao' => 'Principais periódicos nacionais e regionais atualizados diariamente.',
                'status' => 'ativo',
                'ordem' => 2,
            ],
            [
                'nome' => 'Revistas & Periódicos',
                'slug' => 'revistas-periodicos',
                'descricao' => 'Revistas sobre atualidades, ciência, negócios, moda, saúde e decoração.',
                'status' => 'ativo',
                'ordem' => 3,
            ],
            [
                'nome' => 'Quadrinhos & Mangás',
                'slug' => 'quadrinhos-mangas',
                'descricao' => 'Gibis clássicos, graphic novels, mangás japoneses e edições especiais.',
                'status' => 'ativo',
                'ordem' => 4,
            ],
            [
                'nome' => 'Livros & Best-Sellers',
                'slug' => 'livros-best-sellers',
                'descricao' => 'Literatura variada, desenvolvimento pessoal, romances e não-ficção.',
                'status' => 'ativo',
                'ordem' => 5,
            ],
            [
                'nome' => 'Colecionáveis & Álbuns',
                'slug' => 'colecionaveis-albuns',
                'descricao' => 'Álbuns de figurinhas, miniaturas em escala, cards colecionáveis e passatempos.',
                'status' => 'ativo',
                'ordem' => 6,
            ],
            [
                'nome' => 'Papelaria & Conveniência',
                'slug' => 'papelaria-conveniencia',
                'descricao' => 'Itens práticos escolares, canetas, cadernos e utilidades rápidas para o dia a dia.',
                'status' => 'ativo',
                'ordem' => 7,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
