<?php

namespace ArnlInvoices\InvoiceManager\Http\Controllers;

use Illuminate\Routing\Controller;

class WelcomeController extends Controller
{
    public function index()
    {
        return 'Hello from the Arneel package controller!';
    }

    public function indexView()
    {
        return view('invoicemanager::arnl');
    }
}
