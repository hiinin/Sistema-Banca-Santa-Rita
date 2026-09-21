<?php

use App\Models\Configuration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations to update Banca Santa Rita info.
     */
    public function up(): void
    {
        DB::table('configurations')->updateOrInsert(
            ['id' => 1],
            [
                'nome_banca' => 'Banca Santa Rita',
                'descricao' => 'Seu ponto de encontro com a informação, cultura, revistas, jornais e novidades na Praça do Peladão em Maringá.',
                'endereco' => 'Praça 7 de Setembro (Praça do Peladão), s/n - Ao lado do Hospital Bom Samaritano, Zona 05, Maringá - PR',
                'telefone' => '(44) 9842-4758',
                'whatsapp' => '4498424758',
                'horario' => 'Segunda a Sexta: das 08:00 às 18:00 | Sábados: das 09:00 às 17:00',
                'latitude' => '-23.422934',
                'longitude' => '-51.952967',
                'texto_sobre' => "Fundada com o compromisso de manter viva a tradição da leitura e da boa conversa, a **Banca Santa Rita** é seu ponto de referência na Praça do Peladão (Praça 7 de Setembro), ao lado do Hospital Bom Samaritano, em Maringá - PR.\n\nOferecemos um acervo cuidadosamente selecionado com as principais publicações nacionais e internacionais, jornais matinais, as revistas mais conceituadas de atualidades e design, gibis clássicos e novidades em mangás, além de colecionáveis e lançamentos literários.\n\nNosso propósito é proporcionar um atendimento acolhedor e ágil para toda a comunidade de Maringá e região.",
                'updated_at' => now(),
            ]
        );

        Cache::forget(Configuration::CACHE_KEY);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep updated values
    }
};
