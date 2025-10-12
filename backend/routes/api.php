<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\FuncionarioController;
use App\Http\Controllers\Api\ClienteController;

Route::apiResource('empresas', EmpresaController::class);
Route::apiResource('funcionarios', FuncionarioController::class);
Route::apiResource('clientes', ClienteController::class);
