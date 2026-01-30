<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return view('agence.payments.index');
    }

    public function show($id)
    {
        return view('agence.payments.show', compact('id'));
    }       

    public function create()
    {
        return view('agence.payments.create');
    }

    public function edit($id)
    {
        return view('agence.payments.edit', compact('id'));     
    }
}
