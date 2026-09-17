<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Content;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catDestaques = Category::where('slug', 'destaques-novidades')->first() ?? Category::first();
        $catJornais = Category::where('slug', 'jornais-do-dia')->first() ?? Category::first();
        $catRevistas = Category::where('slug', 'revistas-periodicos')->first() ?? Category::first();
        $catColecionaveis = Category::where('slug', 'colecionaveis-albuns')->first() ?? Category::first();
        $catQuadrinhos = Category::where('slug', 'quadrinhos-mangas')->first() ?? Category::first();

        $contents = [
            [
                'titulo' => 'Nova Fachada e Espaço Renovado da Banca Santa Rita',
                'slug' => 'nova-fachada-espaco-renovado-banca-santa-rita',
                'descricao' => 'Conheça o novo layout da Banca Santa Rita, pensado para oferecer mais conforto, acessibilidade e melhor visualização das publicações para nossos clientes.',
                'tipo' => 'foto',
                'categoria_id' => $catDestaques->id,
                'imagem' => 'images/demo-banca-fachada.svg',
                'video_url' => null,
                'ordem' => 1,
                'status' => 'publicado',
                'destaque' => 'sim',
                'data_publicacao' => now()->subDays(2),
            ],
            [
                'titulo' => 'Jornais do Dia Chegando às 06h da Manhã',
                'slug' => 'jornais-do-dia-chegando-as-06h',
                'descricao' => 'Recebemos as principais manchetes do Brasil e do mundo nas primeiras horas do dia. Venha tomar seu café da manhã bem informado conosco.',
                'tipo' => 'foto',
                'categoria_id' => $catJornais->id,
                'imagem' => 'images/demo-jornais-manhas.svg',
                'video_url' => null,
                'ordem' => 2,
                'status' => 'publicado',
                'destaque' => 'sim',
                'data_publicacao' => now()->subDays(3),
            ],
            [
                'titulo' => 'Exposição de Revistas e Publicações Especiais',
                'slug' => 'exposicao-revistas-publicacoes-especiais',
                'descricao' => 'Títulos de tecnologia, negócios, gastronomia, arquitetura e bem-estar prontos para leitura na Banca Santa Rita.',
                'tipo' => 'foto',
                'categoria_id' => $catRevistas->id,
                'imagem' => 'images/demo-revistas-semanais.svg',
                'video_url' => null,
                'ordem' => 3,
                'status' => 'publicado',
                'destaque' => 'não',
                'data_publicacao' => now()->subDays(4),
            ],
            [
                'titulo' => 'Espaço Geek: Gibis Clássicos e Colecionáveis em Alta',
                'slug' => 'espaco-geek-gibis-classicos-colecionaveis',
                'descricao' => 'Para colecionadores e fãs de quadrinhos de todas as idades: novos volumes de mangás, álbuns e edições históricas.',
                'tipo' => 'foto',
                'categoria_id' => $catColecionaveis->id,
                'imagem' => 'images/demo-colecionaveis.svg',
                'video_url' => null,
                'ordem' => 4,
                'status' => 'publicado',
                'destaque' => 'sim',
                'data_publicacao' => now()->subDays(5),
            ],
            [
                'titulo' => 'Tour Virtual: Como Encontrar as Melhores Leituras na Banca',
                'slug' => 'tour-virtual-melhores-leituras-banca',
                'descricao' => 'Assista ao vídeo e veja como organizamos os expositores por áreas de interesse para facilitar sua escolha diária.',
                'tipo' => 'video',
                'categoria_id' => $catDestaques->id,
                'imagem' => 'images/demo-banca-fachada.svg',
                'video_url' => 'https://www.youtube.com/watch?v=ScMzIvxBSi4', // Vídeo de demonstração Creative Commons
                'ordem' => 5,
                'status' => 'publicado',
                'destaque' => 'sim',
                'data_publicacao' => now()->subDays(6),
            ],
            [
                'titulo' => 'Dicas de Leitura da Semana com a Banca Santa Rita',
                'slug' => 'dicas-de-leitura-da-semana',
                'descricao' => 'Apresentamos três lançamentos fascinantes em livros e quadrinhos que acabaram de chegar na nossa exposição.',
                'tipo' => 'video',
                'categoria_id' => $catQuadrinhos->id,
                'imagem' => 'images/demo-revistas-semanais.svg',
                'video_url' => 'https://www.youtube.com/watch?v=kJQP7kiw5Fk',
                'ordem' => 6,
                'status' => 'publicado',
                'destaque' => 'não',
                'data_publicacao' => now()->subDays(7),
            ],
        ];

        foreach ($contents as $item) {
            Content::updateOrCreate(['slug' => $item['slug']], $item);
        }
    }
}
