<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FormStackUrlController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::name('session.')
    ->prefix('session')
    ->group(function(){
        Route::post('login', [LoginController::class, 'login']);
        Route::post('forgot-password', [LoginController::class, 'forgotPassword']);
        Route::post('reset-password', [LoginController::class, 'resetPassword']);
        Route::middleware('auth:sanctum')->post('profile',[LoginController::class, 'profile']);
        Route::middleware('auth:sanctum')->post('change-password',[LoginController::class, 'changePassword']);
        Route::middleware('auth:sanctum')->post('update-profile',[LoginController::class, 'updateProfile']);
        Route::middleware('auth:sanctum')->post('update-profile-image',[LoginController::class, 'updateProfileImage']);
        Route::middleware('auth:sanctum')->post('logout',[LoginController::class, 'logout']);
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

        Route::name('form-stack-url')
        ->prefix('form-stack-url')
        ->group(function (){
            Route::post('showall', [FormStackUrlController::class, 'showall']);
            Route::get('show/{id}', [FormStackUrlController::class, 'show']);
            Route::post('create', [FormStackUrlController::class, 'store']);
            Route::post('update/{id}', [FormStackUrlController::class, 'update']);
            Route::post('destroy/{id}', [FormStackUrlController::class, 'destroy']);
            Route::post('update-status/{id}', [FormStackUrlController::class, 'updateStatus']);
            
        });
    
});


