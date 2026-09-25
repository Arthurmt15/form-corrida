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
        Schema::create('pessoas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->string('nome_social', 150)->nullable();
            $table->enum('tipo_pessoa', ['FISICA', 'JURIDICA'])->default('FISICA');
            $table->string('cpf_cnpj', 18)->unique();
            $table->string('rg_ie', 20)->nullable();
            $table->date('data_nascimento');
            $table->string('genero', 20)->nullable();
            $table->string('email', 150)->unique();
            $table->string('email2', 150)->nullable();
            $table->string('celular', 20);
            $table->boolean('tem_whatsapp')->default(true);
            $table->string('telefone_fixo', 20)->nullable();
            $table->string('cep', 9);
            $table->string('logradouro', 150);
            $table->string('numero', 10);
            $table->string('complemento', 100)->nullable();
            $table->string('bairro', 100);
            $table->string('cidade', 100)->index();
            $table->char('uf', 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoas');
    }
};
