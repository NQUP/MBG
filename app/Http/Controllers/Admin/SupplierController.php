<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'kontak'        => 'nullable|string|max:50',
            'alamat'        => 'nullable|string',
            'keterangan'    => 'nullable|string',
        ]);

        Supplier::create($request->only(['nama_supplier', 'kontak', 'alamat', 'keterangan']) + [
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'kontak'        => 'nullable|string|max:50',
            'alamat'        => 'nullable|string',
            'keterangan'    => 'nullable|string',
        ]);

        $supplier->update($request->only(['nama_supplier', 'kontak', 'alamat', 'keterangan']) + [
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->barangMasuk()->exists()) {
            return back()->with('error', 'Supplier tidak dapat dihapus karena memiliki riwayat transaksi.');
        }

        $supplier->delete();
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }
}