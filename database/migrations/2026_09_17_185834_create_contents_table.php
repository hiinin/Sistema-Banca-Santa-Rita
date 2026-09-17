<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->longText('descricao')->nullable();
            $table->string('tipo')->default('foto')->index(); // 'foto' ou 'video'
            $table->foreignId('categoria_id')->constrained('categories')->cascadeOnDelete();
            $table->string('imagem')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_arquivo')->nullable();
            $table->integer('ordem')->default(0)->index();
            $table->string('status')->default('publicado')->index(); // 'rascunho' ou 'publicado'
            $table->string('destaque')->default('não')->index(); // 'sim' ou 'não'
            $table->dateTime('data_publicacao')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
