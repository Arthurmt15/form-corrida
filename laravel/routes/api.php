<?php

use App\Http\Controllers\Api\InscricaoController;
use Illuminate\Support\Facades\Route;

Route::get('/inscricoes', [InscricaoController::class, 'index'])->middleware('throttle:60,1');
