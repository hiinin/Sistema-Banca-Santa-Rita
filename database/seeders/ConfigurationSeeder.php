<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Configuration::updateOrCreate(
            ['id' => 1],
            [
                'nome_banca' => 'Banca Santa Rita',
                'logo' => null,
                'favicon' => null,
                'descricao' => 'Seu ponto tradicional de encontro com a informação, revistas, gibis, livros e novidades.',
                'endereco' => 'Praça Coronel Fernando Prestes, s/n - Centro, Sorocaba - SP',
                'telefone' => '(15) 3232-1000',
                'whatsapp' => '15998765432',
                'email' => 'contato@bancasantarita.com.br',
                'instagram' => 'bancasantarita',
                'facebook' => 'bancasantaritaoficial',
                'horario' => 'Segunda a Sábado: das 06:00 às 20:00 | Domingos e Feriados: das 06:30 às 14:00',
                'latitude' => '-23.5015',
                'longitude' => '-47.4581',
                'texto_sobre' => "Fundada com o compromisso de manter viva a tradição da leitura e da boa conversa, a **Banca Santa Rita** é muito mais que um ponto comercial: é um ponto histórico de convivência na cidade.\n\nOferecemos um acervo cuidadosamente selecionado com as principais publicações nacionais e internacionais, jornais matinais, as revistas mais conceituadas de atualidades e design, gibis clássicos e novidades em mangás, além de colecionáveis e lançamentos literários.\n\nNosso propósito é proporcionar um atendimento acolhedor e ágil para toda a família.",
            ]
        );
    }
}
