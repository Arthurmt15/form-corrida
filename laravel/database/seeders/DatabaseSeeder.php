<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Contexto: sem seeds iniciais — o app só recebe inscrições via formulário/API.
     */
    public function run(): void
    {
        //
    }
}
