<?php

namespace App\Http\Controllers;

use App\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // ฟังก์ชันสำหรับแสดงบิลทั้งหมด
    public function index()
    {
        $invoices = Invoice::all();
        return response()->json($invoices);
    }

    // ฟังก์ชันสำหรับสร้างบิลใหม่
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'amount' => 'required|numeric',
        ]);

        $invoice = Invoice::create([
            'customer_name' => $request->customer_name,
            'amount' => $request->amount,
        ]);

        return response()->json($invoice, 201);
    }
}
