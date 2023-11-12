<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Get all invoices
 */
Route::get('/get-all-invoices', [InvoiceController::class, 'get_all_invoices']);
Route::get('/search-invoices', [InvoiceController::class, 'search_invoices']);
Route::get('/create-invoice', [InvoiceController::class, 'create_invoice']);
