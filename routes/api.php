<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OlympistsController;
use App\Http\Controllers\EvaluatorsController;
use App\Http\Controllers\EvaluationsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\MedalleroController;

// Público
Route::post('/login', [AuthController::class, 'login']);

// Protegidas
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Importaciones y listados - sólo admin/coordinador
    Route::middleware('role:admin,coordinador')->group(function() {
        Route::get('/olimpistas', [OlympistsController::class, 'index']);
        Route::post('/olimpistas/import', [OlympistsController::class, 'import']);

        Route::get('/evaluadores', [EvaluatorsController::class, 'index']);
        Route::post('/evaluadores', [EvaluatorsController::class, 'store']);
        Route::delete('/evaluadores/{id}', [EvaluatorsController::class, 'destroy']);

        Route::get('/reportes/certificados', [ReportsController::class, 'certificados']);
        Route::get('/reportes/ceremonia', [ReportsController::class, 'ceremonia']);
        Route::get('/reportes/publicacion', [ReportsController::class, 'publicacion']);

        Route::get('/medallero', [MedalleroController::class, 'get']);
        Route::post('/medallero', [MedalleroController::class, 'set']);
    });

    // Evaluaciones: admin, coordinador, evaluador
    Route::middleware('role:admin,coordinador,evaluador')->group(function() {
        Route::get('/evaluaciones', [EvaluationsController::class, 'list']);
        Route::post('/evaluaciones/bulk', [EvaluationsController::class, 'bulk']);
        Route::post('/evaluaciones/reordenar', [EvaluationsController::class, 'reorder']);
        Route::post('/evaluaciones/cerrar-fase', [EvaluationsController::class, 'closePhase']);
    });

});
