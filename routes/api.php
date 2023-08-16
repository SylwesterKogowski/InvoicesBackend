<?php

use App\Modules\Invoices\Api\Web\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::prefix('invoices')->group(
    static function (): void {
        Route::prefix('/{id}')->group(
            static function (): void {
                Route::get('', [InvoiceController::class, 'get'])->name('invoices.get');
                Route::post('/approve', [InvoiceController::class, 'approve'])->name('invoices.approve');
                Route::post('/reject', [InvoiceController::class, 'reject'])->name('invoices.reject');
            }
        );
    }
);
