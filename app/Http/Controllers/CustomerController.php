<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Get all customers
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $customers = Customer::where('customer_name', 'like', "%$search%")
                             ->paginate($perPage);

        return response()->json($customers);
    }

    // Store a new customer
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_code' => 'required|unique:customers',
            'customer_name' => 'required|string',
            'contact_person' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'type' => 'nullable|string',
            'credit_limit' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $customer = Customer::create($validated);

        return response()->json($customer, 201);
    }

    // Update an existing customer
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $validated = $request->validate([
            'customer_code' => 'required',
            'customer_name' => 'required|string',
            'contact_person' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'type' => 'nullable|string',
            'credit_limit' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return response()->json($customer, 200);
    }

    // Delete a customer
    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->delete();

        return response()->json(['message' => 'Customer deleted'], 200);
    }
}
