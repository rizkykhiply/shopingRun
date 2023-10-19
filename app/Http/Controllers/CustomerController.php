<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Models\Customer;
use App\Models\Kondisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index()
{
    if (request()->wantsJson()) {
        // Jika permintaan adalah JSON, kembalikan data pelanggan dalam format JSON
        return response(Customer::with('kondisi1')->get());
    }

    // Jika bukan permintaan JSON, ambil semua data pelanggan dengan relasi kondisi dan urutkan berdasarkan yang terbaru.
    $customers = Customer::with('kondisi1')->latest()->paginate(10);

    // Lebih lanjut, lewatkan data pelanggan ke tampilan
    return view('customers.index', compact('customers'));
}

    public function create()
    {
        $kondisiOptions = Kondisi::all();
        return view('customers.create', compact('kondisiOptions'));
    }

    public function store(CustomerStoreRequest $request)
    {
        $customer = Customer::create([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'hp' => $request->hp,
            'rek' => $request->rek,
            'kondisi_id' => $request->kondisi, // Sesuaikan dengan nama kolom relasi yang digunakan
            'saldo' => $request->saldo,
            'poin' => $request->poin,
            'user_id' => $request->user()->id,
        ]);

        if (!$customer) {
            return redirect()->back()->with('error', 'Maaf, ada masalah saat membuat pelanggan.');
        }

        return redirect()->route('customers.index')->with('success', 'Berhasil, pelanggan Anda telah dibuat.');
    }

    public function edit(Customer $customer)
    {
        $kondisiOptions = Kondisi::all();
        return view('customers.edit', compact('customer', 'kondisiOptions'));
    }

    public function update(Request $request, Customer $customer)
    {
        $customer->nama = $request->nama;
        $customer->nik = $request->nik;
        $customer->alamat = $request->alamat;
        $customer->hp = $request->hp;
        $customer->rek = $request->rek;
        $customer->kondisi_id = $request->kondisi; // Sesuaikan dengan nama kolom relasi yang digunakan
        $customer->poin = $request->poin;
        $customer->saldo = $request->saldo;

        if (!$customer->save()) {
            return redirect()->back()->with('error', 'Maaf, ada masalah saat memperbarui pelanggan.');
        }

        return redirect()->route('customers.index')->with('success', 'Berhasil, pelanggan Anda telah diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->avatar) {
            Storage::delete($customer->avatar);
        }

        $customer->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
