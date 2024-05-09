<?php

use App\Http\Controllers\Api\CastMemberController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\VideoController;
use Illuminate\Support\Facades\Route;

// Autenticação e Autorização
Route::middleware(['auth:api', 'can:admin-catalog'])->group(function () {
    Route::get('/me', function () {
        // $payload = json_decode(Auth::token());
        // $realmAccess = $payload->realm_access ?? null;
        // $roles = $realmAccess->roles ?? [];
        // $hasRole = in_array('admin-catalog', $roles);
        // dd($hasRole);
        return 'sucess';
    });

    Route::apiResource('/videos', VideoController::class);

    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource(
        name: '/genres',
        controller: GenreController::class
    );
    Route::apiResource('/cast_members', CastMemberController::class);

});

// Incluídos no bloco de Middleware para testar Autenticação
// Route::apiResource('/videos', VideoController::class);

// Route::apiResource('/categories', CategoryController::class);
// Route::apiResource(
// name: '/genres',
// controller: GenreController::class
// );
// Route::apiResource('/cast_members', CastMemberController::class);

Route::get('/', function () {
    return response()->json(['message' => 'Sucess'], 200);
});
