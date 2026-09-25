<?php

use App\Http\Controllers\InscricaoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/inscricoes/create');

Route::get('/inscricoes', [InscricaoController::class, 'index'])->name('inscricoes.index');
Route::get('/inscricoes/create', [InscricaoController::class, 'create'])->name('inscricoes.create');
Route::post('/inscricoes', [InscricaoController::class, 'store'])
    ->middleware('throttle:10,1') // anti-spam: 10 envios/min por IP
    ->name('inscricoes.store');

Route::view('/consulta', 'inscricoes.consulta')->name('consulta');
