<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TenantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'barcode'=> $this->barcode,
            'nama' => $this->nama,
            'jenis' => $this->jenis,
            'image' => $this->image,
            'created_at' => $this->created_at,
            'image_url' => Storage::url($this->image)
        ];
    }
}
