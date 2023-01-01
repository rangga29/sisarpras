<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsItemCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cons_sub_category_id' => ['required'],
            'brand_id' => ['required'],
            'shop_id' => ['required'],
            'fund_id' => ['required'],
            'room_id' => ['required'],
            'unit_id' => ['required'],
            'item_code' => ['nullable'],
            'name' => ['required'],
            'initial_amount' => ['required'],
            'taken_amount' => ['nullable'],
            'stock_amount' => ['nullable'],
            'price' => ['required'],
            'purchase_date' => ['required'],
            'image' => 'required|image',
            'receipt' => 'required|file|mimes:pdf',
            'description' => ['nullable']
        ];
    }

    public function messages()
    {
        return [
            'cons_sub_category_id.required' => 'Nama Sub Kategori Harus Diisi',
            'brand_id.required' => 'Nama Merk Harus Diisi',
            'vendor_id.required' => 'Nama Vendor Harus Diisi',
            'source_id.required' => 'Nama Sumber Dana Harus Diisi',
            'room_id.required' => 'Nama Ruangan Harus Diisi',
            'unit_id.required' => 'Nama Unit Harus Diisi',
            'name.required' => 'Nama Barang Harus Diisi',
            'initial_amount.required' => 'Jumlah Beli Harus Diisi',
            'price.required' => 'Harga Barang Harus Diisi',
            'purchase_date.required' => 'Tanggal Beli Harus Diisi',
            'image.required' => 'Foto Barang Harus Diisi',
            'image.image' => 'Foto Barang Harus Berupa Gambar',
            'receipt.required' => 'Tanda Terima Barang Harus Diisi',
            'receipt.mimes' => 'Tanda Terima Barang Harus Berupa PDF'
        ];
    }
}