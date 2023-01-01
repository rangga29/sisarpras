<?php

namespace App\Http\Controllers;

use App\Http\Requests\NonConsConditionCreateRequest;
use App\Http\Requests\NonConsConditionEditRequest;
use App\Models\ConsItem;
use App\Models\NonConsCondition;
use App\Models\NonConsItem;
use App\Models\Unit;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class NonConsConditionController extends Controller
{
    public function index($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.conditions.index', [
            'conditions' => NonConsCondition::orderBy('id')->get(),
            'unit' => $unitData
        ]);
    }

    public function create($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.conditions.create', [
            'unit' => $unitData
        ]);
    }

    public function store($unit, NonConsConditionCreateRequest $request)
    {
        $validateData = $request->validated();
        NonConsCondition::create($validateData);
        return redirect()->route('non-consumable-items.conditions', $unit)->withSuccess('Data Kondisi Berhasil Ditambahkan');
    }

    public function edit($unit, NonConsCondition $condition)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.conditions.edit', [
            'condition' => $condition,
            'unit' => $unitData
        ]);
    }

    public function update($unit, NonConsConditionEditRequest $request, NonConsCondition $condition)
    {
        $validateData = $request->validated();
        $condition->update($validateData);
        return redirect()->route('non-consumable-items.conditions', $unit)->withSuccess('Data Kondisi Berhasil Diubah');
    }

    public function delete($unit, NonConsCondition $condition)
    {
        $item = NonConsItem::where('non_cons_condition_id', $condition->id)->get();
        if ($item->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Barang');
        }

        $condition->delete();
        return redirect()->route('non-consumable-items.conditions', $unit)->withSuccess('Data Kondisi Berhasil Dihapus');
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(NonConsCondition::class, 'slug', $request->name);
        return response()->json(['slug' => $slug]);
    }
}