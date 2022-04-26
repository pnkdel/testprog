<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [HomeController::class, 'authenticate']);
Route::post('/addperson', [HomeController::class, 'addperson']);
Route::post('/updateperson', [HomeController::class, 'updateperson']);
Route::post('/getIndividualData', [HomeController::class, 'getIndividualData']);
Route::post('/deleteIndividualData', [HomeController::class, 'deleteIndividualData']);
Route::get('/verifyjwt', [HomeController::class, 'verifyJwt']);
Route::get('/listall', [HomeController::class, 'listall']);
//Route::get('/test', [HomeController::class, 'login']);
