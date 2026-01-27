<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FormStackUrlController;
use App\Http\Controllers\Admin\WebsiteUrlController;
use App\Http\Controllers\Admin\ImportantInfoController;
use App\Http\Controllers\Admin\UploadPDFController;

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

         Route::name('website-url')
        ->prefix('website-url')
        ->group(function (){
            Route::post('showall', [WebsiteUrlController::class, 'showall']);
            Route::get('show/{id}', [WebsiteUrlController::class, 'show']);
            Route::post('create', [WebsiteUrlController::class, 'store']);
            Route::post('update/{id}', [WebsiteUrlController::class, 'update']);
            Route::post('destroy/{id}', [WebsiteUrlController::class, 'destroy']);
            Route::post('update-status/{id}', [WebsiteUrlController::class, 'updateStatus']);
            
        });

         Route::name('important-info')
        ->prefix('important-info')
        ->group(function (){
            Route::post('showall', [ImportantInfoController::class, 'showall']);
            Route::get('show/{id}', [ImportantInfoController::class, 'show']);
            Route::post('create', [ImportantInfoController::class, 'store']);
            Route::post('update/{id}', [ImportantInfoController::class, 'update']);
            Route::post('destroy/{id}', [ImportantInfoController::class, 'destroy']);
            Route::post('update-status/{id}', [ImportantInfoController::class, 'updateStatus']);
            
        });

        Route::name('upload-pdf')
        ->prefix('upload-pdf')
        ->group(function (){
            Route::post('showall', [UploadPDFController::class, 'showall']);
            Route::get('show/{id}', [UploadPDFController::class, 'show']);
            Route::post('create', [UploadPDFController::class, 'store']);
            Route::post('update/{id}', [UploadPDFController::class, 'update']);
            Route::post('destroy/{id}', [UploadPDFController::class, 'destroy']);
            Route::post('update-status/{id}', [UploadPDFController::class, 'updateStatus']);
            Route::post('download-pdf/{id}', [UploadPDFController::class, 'downloadPDF']);

        });
    
});


