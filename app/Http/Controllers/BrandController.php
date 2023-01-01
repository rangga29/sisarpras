<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Http\Requests\BrandEditRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\BrandCreateRequest;
use App\Models\ConsItem;
use App\Models\NonConsItem;
use Cviebrock\EloquentSluggable\Services\SlugService;

class BrandController extends Controller
{
    public function index()
    {
        return view('brands.index', [
            'brands' => Brand::orderBy('id')->get()
        ]);
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(BrandCreateRequest $request)
    {
        $validateData = $request->validated();

        if ($request->image) {
            $filename = uniqid() . '.png';
            $imgData = Image::make($validateData['image'])->fit(120)->encode('png');
            Storage::put('public/brands/' . $filename, $imgData);
            $validateData['image'] = $filename;
        } else {
            $validateData['image'] = 'default-brand.png';
        }

        Brand::create($validateData);
        return redirect()->route('brands')->withSuccess('Data Merk Berhasil Ditambahkan');
    }

    public function edit(Brand $brand)
    {
        return view('brands.edit', [
            'brand' => $brand
        ]);
    }

    public function update(BrandEditRequest $request, Brand $brand)
    {
        $validateData = $request->validated();

        if ($request->image) {
            $filename = uniqid() . '.png';
            $imgData = Image::make($validateData['image'])->fit(120)->encode('png');
            Storage::put('public/brands/' . $filename, $imgData);
            if ($brand->image !== 'default-brand.png') {
                Storage::delete('public/brands/' . $brand->image);
            }
            $validateData['image'] = $filename;
        }
        $brand->update($validateData);
        return redirect()->route('brands')->withSuccess('Data Merk Berhasil Diubah');
    }

    public function delete(Brand $brand)
    {
        $cons_item = ConsItem::where('brand_id', $brand->id)->get();
        if ($cons_item->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Barang Habis Pakai');
        }

        $non_cons_item = NonConsItem::where('brand_id', $brand->id)->get();
        if ($non_cons_item->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Barang Tidak Habis Pakai');
        }

        if ($brand->image !== 'default-brand.png') {
            Storage::delete('public/brands/' . $brand->image);
        }
        $brand->delete();
        return redirect()->route('brands')->withSuccess('Data Merk Berhasil Dihapus');
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(Brand::class, 'slug', $request->name);
        return response()->json(['slug' => $slug]);
    }
}