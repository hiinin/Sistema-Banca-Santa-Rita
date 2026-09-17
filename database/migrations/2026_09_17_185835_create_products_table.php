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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->text('descricao')->nullable();
            $table->string('imagem')->nullable();
            $table->decimal('preco', 10, 2)->nullable(); // Preço de capa informativo opcional
            $table->foreignId('categoria_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('destaque')->default('não')->index(); // 'sim' ou 'não'
            $table->string('status')->default('publicado')->index(); // 'rascunho' ou 'publicado'
            $table->integer('ordem')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
