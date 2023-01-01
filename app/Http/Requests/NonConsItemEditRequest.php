<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NonConsItemEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'non_cons_sub_category_id' => ['required'],
            'brand_id' => ['required'],
            'shop_id' => ['required'],
            'fund_id' => ['required'],
            'room_id' => ['required'],
            'non_cons_condition_id' => ['required'],
            'unit_id' => ['required'],
            'item_code' => ['nullable'],
            'item_number' => ['required'],
            'name' => ['required'],
            'price' => ['required'],
            'purchase_date' => ['required'],
            'include' => ['nullable'],
            'image' => 'image',
            'receipt' => 'file|mimes:pdf',
            'description' => ['nullable'],
            'availability' => ['nullable']
        ];
    }

    public function messages()
    {
        return [
            'non_cons_sub_category_id.required' => 'Nama Sub Kategori Harus Diisi',
            'brand_id.required' => 'Nama Merk Harus Diisi',
            'vendor_id.required' => 'Nama Vendor Harus Diisi',
            'source_id.required' => 'Nama Sumber Dana Harus Diisi',
            'room_id.required' => 'Nama Ruangan Harus Diisi',
            'non_cons_condition_id.required' => 'Nama Kondisi Harus Diisi',
            'unit_id.required' => 'Nama Unit Harus Diisi',
            'item_number.required' => 'Nomor Barang Harus Diisi',
            'name.required' => 'Nama Barang Harus Diisi',
            'price.required' => 'Harga Barang Harus Diisi',
            'purchase_date.required' => 'Tanggal Beli Harus Diisi',
            'image.image' => 'Foto Barang Harus Berupa Gambar',
            'receipt.mimes' => 'Tanda Terima Barang Harus Berupa PDF'
        ];
    }
}