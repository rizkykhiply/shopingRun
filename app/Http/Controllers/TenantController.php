<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantStoreRequest;
use App\Http\Requests\TenantUpdateRequest;
use App\Http\Resources\TenantResource;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tenants = new Tenant();
        if ($request->search) {
            $tenants = $tenants->where('name', 'LIKE', "%{$request->search}%");
        }
        $tenants = $tenants->latest()->paginate(10);
        if (request()->wantsJson()) {
            return TenantResource::collection($tenants);
        }
        return view('tenants.index')->with('tenants', $tenants);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TenantStoreRequest $request)
    {
        $image_path = '';

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('tenants', 'public');
        }

        $tenant = Tenant::create([
            'barcode' => $request->barcode,
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'image' => $image_path
        ]);

        if (!$tenant) {
            return redirect()->back()->with('error', 'Sorry, there a problem while creating tenant.');
        }
        return redirect()->route('tenants.index')->with('success', 'Success, you tenant have been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function show(Tenant $tenant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function edit(Tenant $tenant)
    {
        return view('tenants.edit')->with('tenant', $tenant);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function update(TenantUpdateRequest $request, Tenant $tenant)
    {
        $tenant->barcode = $request->barcode;
        $tenant->nama = $request->nama;
        $tenant->jenis = $request->jenis;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($tenant->image) {
                Storage::delete($tenant->image);
            }
            // Store image
            $image_path = $request->file('image')->store('tenants', 'public');
            // Save to Database
            $tenant->image = $image_path;
        }

        if (!$tenant->save()) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while updating tenant.');
        }
        return redirect()->route('tenants.index')->with('success', 'Success, your tenant have been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tenant $tenant)
    {
        if ($tenant->image) {
            Storage::delete($tenant->image);
        }
        $tenant->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
