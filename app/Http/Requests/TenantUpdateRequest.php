<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TenantUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $tenant_id = $this->route('tenant')->id;
        return [
            'barcode' => 'required|string|max:255',
            'nama' => 'nullable|string',
            'jenis' => 'required|boolean',
            'image' => 'nullable|image',
        ];
    }
}
