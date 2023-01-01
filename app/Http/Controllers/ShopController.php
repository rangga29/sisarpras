<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopCreateRequest;
use App\Http\Requests\ShopEditRequest;
use App\Models\ConsItem;
use App\Models\NonConsItem;
use App\Models\Shop;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ShopController extends Controller
{
    public function index()
    {
        return view('shops.index', [
            'shops' => Shop::orderBy('id')->get()
        ]);
    }

    public function create()
    {
        return view('shops.create');
    }

    public function store(ShopCreateRequest $request)
    {
        $validateData = $request->validated();

        if ($request->image) {
            $filename = uniqid() . '.png';
            $imgData = Image::make($validateData['image'])->fit(120)->encode('png');
            Storage::put('public/shops/' . $filename, $imgData);
            $validateData['image'] = $filename;
        } else {
            $validateData['image'] = 'default-shop.png';
        }

        Shop::create($validateData);
        return redirect()->route('shops')->withSuccess('Data Vendor Berhasil Ditambahkan');
    }

    public function show(Shop $shop)
    {
        //
    }

    public function edit(Shop $shop)
    {
        return view('shops.edit', [
            'shop' => $shop
        ]);
    }

    public function update(ShopEditRequest $request, Shop $shop)
    {
        $validateData = $request->validated();

        if ($request->image) {
            $filename = uniqid() . '.png';
            $imgData = Image::make($validateData['image'])->fit(120)->encode('png');
            Storage::put('public/shops/' . $filename, $imgData);
            if ($shop->image !== 'default-shop.png') {
                Storage::delete('public/shops/' . $shop->image);
            }
            $validateData['image'] = $filename;
        }
        $shop->update($validateData);
        return redirect()->route('shops')->withSuccess('Data Vendor Berhasil Diubah');
    }

    public function delete(Shop $shop)
    {
        $cons_item = ConsItem::where('shop_id', $shop->id)->get();
        if ($cons_item->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Barang Habis Pakai');
        }

        $non_cons_item = NonConsItem::where('shop_id', $shop->id)->get();
        if ($non_cons_item->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Barang Tidak Habis Pakai');
        }

        if ($shop->image !== 'default-shop.png') {
            Storage::delete('public/shops/' . $shop->image);
        }
        $shop->delete();
        return redirect()->route('shops')->withSuccess('Data Vendor Berhasil Dihapus');
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(Shop::class, 'slug', $request->name);
        return response()->json(['slug' => $slug]);
    }
}