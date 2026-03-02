<?php

use App\Http\Controllers\Admin\OrderPrintController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/print/order/{order}', OrderPrintController::class)->name('admin.order.print');
});
