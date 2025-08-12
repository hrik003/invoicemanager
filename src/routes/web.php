<?php

use Illuminate\Support\Facades\Route;
use ArnlInvoices\InvoiceManager\Http\Controllers\WelcomeController;
use ArnlInvoices\InvoiceManager\Http\Controllers\InvoiceController;

Route::get('invoicemanager/test', function () {
    // return 'It works!';
    return view('invoicemanager::arnl');
});
Route::get('hello', [WelcomeController::class, 'index']);
// Route::get('invoicemanager/helloView', [WelcomeController::class, 'indexView']);
// Route::post('/api/arnlinvoice/validate', [InvoiceController::class, 'validateKey']);
// Route::get('invoicemanager/invoice-view', [InvoiceController::class, 'index']);

Route::middleware(['web','auth'])->group(function () {
    Route::resource('invoices','InvoiceController')->except(['edit','update']); // add as needed
    Route::get('invoices/{invoice}/download',[InvoiceController::class, 'downloadPdf'])->name('invoices.download');
    Route::post('invoices/{invoice}/email',[InvoiceController::class, 'emailInvoice'])->name('invoices.email');
});
