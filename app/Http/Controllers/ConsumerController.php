<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsumerCreateRequest;
use App\Http\Requests\ConsumerEditRequest;
use App\Models\Consumer;
use App\Models\LoanItem;
use App\Models\PickupItem;
use App\Models\Position;
use App\Models\Unit;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class ConsumerController extends Controller
{
    public function index($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumers.index', [
            'consumers' => Consumer::where('unit_id', $unitData->id)->orderBy('id')->get(),
            'unit' => $unitData
        ]);
    }

    public function create($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumers.create', [
            'positions' => Position::orderBy('id')->get(),
            'unit' => $unitData
        ]);
    }

    public function store($unit, ConsumerCreateRequest $request)
    {
        $validateData = $request->validated();
        Consumer::create($validateData);
        return redirect()->route('consumers', $unit)->withSuccess('Data Pengguna Berhasil Ditambahkan');
    }

    public function show(Consumer $consumer)
    {
        //
    }

    public function edit($unit, Consumer $consumer)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumers.edit', [
            'consumer' => $consumer,
            'positions' => Position::orderBy('id')->get(),
            'unit' => $unitData
        ]);
    }

    public function update($unit, ConsumerEditRequest $request, Consumer $consumer)
    {
        $validateData = $request->validated();
        $consumer->update($validateData);
        return redirect()->route('consumers', $unit)->withSuccess('Data Pengguna Berhasil Diubah');
    }

    public function delete($unit, Consumer $consumer)
    {
        $pickup = PickupItem::where('consumer_id', $consumer->id)->get();
        if ($pickup->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Pengambilan');
        }

        $loan = LoanItem::where('consumer_id', $consumer->id)->get();
        if ($loan->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Peminjaman');
        }

        $consumer->delete();
        return redirect()->route('consumers', $unit)->withSuccess('Data Pengguna Berhasil Dihapus');
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(Consumer::class, 'slug', $request->name);
        return response()->json(['slug' => $slug]);
    }
}