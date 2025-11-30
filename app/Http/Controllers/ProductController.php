<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier; // <-- Tambahkan ini

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // <-- Tambahkan Request $request
    {
        // Modifikasi query untuk Eager Loading dan Search
        $query = Product::with('supplier'); // [cite: 65, 228]

        if ($request->has('search') && $request->search != '') { // [cite: 67]
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', '%' . $search . '%'); // [cite: 72]
            });
        }

        $data = $query->paginate(2); // [cite: 76]
        
        // Return view with data
        return view("master-data.product-master.index-product", compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all(); // [cite: 368]
        return view("master-data.product-master.create-product", compact('suppliers')); // [cite: 370]
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // Validate input data
        $validasi_data = $request->validate([
            'product_name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'type' => 'required|string|max:50',
            'information' => 'nullable|string',
            'qty' => 'required|integer',
            'producer' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id', // <-- Tambahkan validasi ini [cite: 362]
        ]);

        // Process saving data to the database
        Product::create($validasi_data);

        return redirect()->back()->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find product by ID
        $product = Product::findOrFail($id);
        $suppliers = Supplier::all(); // <-- Ambil data supplier [cite: 390]
    
        // Return edit view with product data
        return view("master-data.product-master.edit-product", compact('product', 'suppliers')); // <-- Kirim suppliers ke view
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate input
        $request->validate([
            'product_name' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'information' => 'nullable|string',
            'qty' => 'required|integer|min:1',
            'producer' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id', // <-- Tambahkan validasi ini [cite: 362]
        ]);

        // Find product and update
        $product = Product::findOrFail($id);
        $product->update([
            'product_name' => $request->product_name,
            'unit' => $request->unit,
            'type' => $request->type,
            'information' => $request->information,
            'qty' => $request->qty,
            'producer' => $request->producer,
            'supplier_id' => $request->supplier_id, // <-- Tambahkan ini untuk update
        ]);

        return redirect()->back()->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // (Tidak ada di instruksi, tapi sebaiknya lengkapi)
        $product = Product::findOrFail($id);
        $product->delete();
        
        return redirect()->route('product-index')->with('success', 'Product deleted successfully!');
    }
}