<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\MergeController;

Route::get('/contacts',[ContactController::class,'index']);
Route::post('/contacts/store',[ContactController::class,'store']);

// Add update route
Route::post('/contacts/update/{id}', [ContactController::class,'update']);

// Change delete route to POST for simplicity
Route::post('/contacts/delete/{id}',[ContactController::class,'destroy']);

Route::post('/contacts/list',[ContactController::class,'list']);

Route::post('/custom-fields/store',[CustomFieldController::class,'store']);
Route::delete('/custom-fields/{id}',[CustomFieldController::class,'destroy']);

Route::post('/contacts/merge',[MergeController::class,'merge']);

