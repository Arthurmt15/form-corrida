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
        Schema::create('inscricoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained('pessoas')->cascadeOnDelete();
            $table->enum('distancia', ['5km', '10km', '21km', '42km'])->index();
            $table->enum('tamanho_camiseta', ['PP', 'P', 'M', 'G', 'GG']);
            $table->string('categoria', 50);
            $table->string('equipe', 100)->nullable();
            $table->string('origem', 50)->default('Site');
            $table->enum('status_cadastro', ['Ativo', 'Inativo', 'Bloqueado'])->default('Ativo')->index();
            $table->boolean('aceite_regulamento')->default(false);
            $table->timestamps();
            $table->unique(['pessoa_id', 'distancia']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscricoes');
    }
};
