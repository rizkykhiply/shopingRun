<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Models\Customer;
use App\Models\Kondisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index(Request $request)
{
    if ($request->wantsJson()) {
        $customers = Customer::with('kondisi1')->get();

        // Check if a search query is provided
        $searchQuery = $request->input('search');
        if ($searchQuery) {
            $customers = $customers->where(function ($query) use ($searchQuery) {
                $query->where('nama', 'like', '%' . $searchQuery . '%')
                      ->orWhere('hp', 'like', '%' . $searchQuery . '%')
                      ->orWhere('nik', 'like', '%' . $searchQuery . '%');
            });
        }

        return response($customers);
    }

    $searchQuery = $request->input('search');
    $customers = Customer::with('kondisi1')
        ->when($searchQuery, function ($query) use ($searchQuery) {
            return $query->where('nama', 'like', '%' . $searchQuery . '%')
                         ->orWhere('hp', 'like', '%' . $searchQuery . '%')
                         ->orWhere('nik', 'like', '%' . $searchQuery . '%');
        })
        ->latest()
        ->paginate(10);

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
            'kondisi_id' => $request->kondisi,
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
        $customer->kondisi_id = $request->kondisi; 
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
