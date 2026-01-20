<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::name('session.')
    ->prefix('session')
    ->group(function(){
        Route::post('login', [LoginController::class, 'login'])->name('login');
        Route::middleware('auth:sanctum')->post('logout',[SessionController::class, 'logout']);
    }
);

Route::name('admin.')
    ->middleware(['auth:sanctum'])
    ->prefix('admin')
    ->group(function (){

     Route::name('user')
        ->prefix('user')
        ->group(function (){
            Route::post('showall', [UserController::class, 'showall']);
            Route::get('show/{id}', [UserController::class, 'show']);
            Route::post('create', [UserController::class, 'store']);
            Route::post('update/{id}', [UserController::class, 'update']);
            Route::post('destroy/{id}', [UserController::class, 'destroy']);
            Route::post('update-status/{id}', [UserController::class, 'updateStatus']);
            
        });
    
});


