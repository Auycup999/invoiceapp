<?php

namespace App\Http\Controllers;

use App\Product;
use App\Sale;
use App\SaleItem;
use App\Customer;
use App\sales_products;
use DB;
use Log;
use Illuminate\Http\Request;
use PDF;
// use Barryvdh\DomPDF\Facade as PDF;

use Dompdf\Dompdf;
use Dompdf\Options;
class SalesController extends Controller
{

    public function index()
    {
        $customers = Customer::all(); // Fetch all customers
        return response()->json($customers);
    }
    public function searchInvoices(Request $request)
    {
   // รับชื่อของลูกค้าที่พิมพ์มา
   $customerName = $request->input('customer_name');

   // ค้นหาบิลที่มีชื่อลูกค้าในรายการขาย
   $invoices = Sale::whereHas('customer', function($query) use ($customerName) {
       $query->where('customer_name', 'like', '%' . $customerName . '%'); // เปลี่ยน 'customer_name' เป็นชื่อคอลัมน์จริงในฐานข้อมูล
   })
   ->get(['invoice_number', 'total','created_at']); // เลือกข้อมูลที่ต้องการ

   return response()->json($invoices); // ส่งผลลัพธ์กลับไป
    }

    public function getproductsales()
    {
        $Products = Product::all(); // Fetch all customers
        return response()->json($Products);
    }
    public function printInvoice($invoiceNumber)
    {
        // ดึงข้อมูลการขายจากหมายเลขเอกสาร
        $sale = Sale::where('invoice_number', $invoiceNumber)->first();

        // ถ้าไม่พบบิล
        if (!$sale) {
            return response()->json(['message' => 'Invoice not found'], 404);
        }

        // ดึงรายการสินค้าที่ขายในบิลนี้
        $products = sales_products::where('sale_id', $sale->id)->get();

        // สร้าง PDF
        $pdf = PDF::loadView('sales.invoice_pdf', compact('sale', 'products'));

        // ส่งไฟล์ PDF กลับไป
        return $pdf->download("invoice_{$invoiceNumber}.pdf");
    }
    // Store a new sale
    public function store(Request $request)
    {
        $sale = $request->all();
    
        // Log the sale data for debugging
        \Log::info('Sale Data:', $request->all());
    
        // Start a transaction to ensure data consistency
        \DB::beginTransaction();
    
        // Step 1: Get the latest invoice number and increment it by 1
        $latestSale = Sale::orderBy('id', 'desc')->first(); // Get the latest sale by id
    
        // Extract the number after "INV" and increment it by 1
        $lastInvoiceNumber = $latestSale ? (int) substr($latestSale->invoice_number, 3) : 0;
        $newInvoiceNumber = 'INV' . str_pad($lastInvoiceNumber + 1, 6, '0', STR_PAD_LEFT); // Generate new invoice number
    
        // Step 2: Insert Sale Data first to get the sale_id
        $sale = new Sale(); // Assuming you have a Sale model
        $sale->customer_id = $request->customer_id;
        $sale->payment_method = $request->payment_method;
        $sale->total = $request->total;
        $sale->invoice_number = $newInvoiceNumber; // Use the new invoice number
        $sale->due_date = $request->due_date;
        $sale->save();
    
        // Step 3: Insert sale items
        foreach ($request->items as $item) {
            if ($item['product_id'] != null) {
                sales_products::create([
                    'sale_id' => $sale->id, // Link the sale_id
                    'customer_id' => $request->customer_id,
                    'product_id' => $item['product_id'],
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        }
    
        // Get the sale products to generate the PDF
        $products = DB::table('sales_products')
            ->join('products', 'products.id', '=', 'sales_products.product_id')
            ->select('sales_products.name', 'sales_products.quantity', 'products.unit', 'sales_products.price')
            ->where('sales_products.sale_id', '=', $sale->id)
            ->get();
    
        // Get the sale data for the PDF
        $sale_pdf = DB::table('sales')
            ->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->select('sales.id', 'sales.customer_id', 'customers.customer_name', 'customers.address', 'customers.phone', 'sales.invoice_number', 'sales.payment_method', 'sales.total', 'sales.due_date', 'sales.created_at')
            ->where('sales.id', '=', $sale->id)
            ->first();
    
        // Commit the transaction
        \DB::commit();
    
        // Generate PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('fontDir', storage_path('fonts/'));
        $options->set('fontCache', storage_path('fonts/'));
        $options->set('isHtml5ParserEnabled', true);
    
        $dompdf = new Dompdf($options);
        $pdf = PDF::loadView('sales.invoice_pdf', compact('sale_pdf', 'products'));
        $pdf->setPaper('A4', 'portrait');
    
        // Save the PDF
        $pdfPath = storage_path('app/public/invoices/invoice_' . $sale->invoice_number . '.pdf');
        $pdf->save($pdfPath);
    
        // Return the URL of the saved PDF
        return response()->json(['url' => asset('storage/invoices/invoice_' . $sale->invoice_number . '.pdf')]);
    }
    public function getLatestInvoiceNumber()
    {
        // ดึงหมายเลขเอกสารล่าสุดจากตาราง sales
        $latestSale = Sale::orderBy('id', 'desc')->first(); // เรียงจาก ID ล่าสุด (ล่าสุดสุด)

        // ถ้ามีข้อมูลการขาย
        if ($latestSale) {
            // ดึงหมายเลขเอกสารล่าสุด
            $lastInvoiceNumber = (int) substr($latestSale->invoice_number, 3); // ตัด "INV" ออก
        } else {
            $lastInvoiceNumber = 0; // ถ้ายังไม่มีการขายใด ๆ
        }

        // ส่งค่าผลลัพธ์กลับไป
        return response()->json(['last_invoice_number' => $lastInvoiceNumber]);
    }
        // Log::info('Sale Data:', $request->all());
        //     Log::debug($request->customer_id);


        // $validated = $request->validate([
        //     'items' => 'required|array',
        //     'items.*.product_id' => 'required',
        //     'items.*.quantity' => 'required',
        //     'items.*.price' => 'required',
        // ]);
    
        // Log::alert($validated);
        // // Create the sale record
        // $sale = product_sale::create($validated);
   
        // Attach the products to the sale
        // foreach ($validated['items'] as $item) {
        //     // $product = Product::find($item['product_id']);
        //     // $product->decrement('stock', $item['quantity']); // Decrease stock

        //     product_sale::create([
        //         'sale_id' => $sale->id,
        //         'product_id' => $item['product_id'],
        //         'quantity' => $item['quantity'],
        //         'price' => $item['price'],
        //     ]);
        // }

        // return response()->json($sale, 201);
    
}
