<?php

namespace App\Http\Controllers;

use App\Http\Requests\FundCreateRequest;
use App\Http\Requests\FundEditRequest;
use App\Models\ConsItem;
use App\Models\Fund;
use App\Models\NonConsItem;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class FundController extends Controller
{
    public function index()
    {
        return view('funds.index', [
            'funds' => Fund::orderBy('id')->get()
        ]);
    }

    public function create()
    {
        return view('funds.create');
    }

    public function store(FundCreateRequest $request)
    {
        $validateData = $request->validated();
        Fund::create($validateData);
        return redirect()->route('funds')->withSuccess('Data Sumber Dana Berhasil Ditambahkan');
    }

    public function show(Fund $fund)
    {
        //
    }

    public function edit(Fund $fund)
    {
        return view('funds.edit', [
            'fund' => $fund
        ]);
    }

    public function update(FundEditRequest $request, Fund $fund)
    {
        $validateData = $request->validated();
        $fund->update($validateData);
        return redirect()->route('funds')->withSuccess('Data Sumber Dana Berhasil Diubah');
    }

    public function delete(Fund $fund)
    {
        $cons_item = ConsItem::where('fund_id', $fund->id)->get();
        if ($cons_item->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Barang Habis Pakai');
        }

        $non_cons_item = NonConsItem::where('fund_id', $fund->id)->get();
        if ($non_cons_item->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Barang Tidak Habis Pakai');
        }

        $fund->delete();
        return redirect()->route('funds')->withSuccess('Data Sumber Dana Berhasil Dihapus');
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(Fund::class, 'slug', $request->name);
        return response()->json(['slug' => $slug]);
    }
}