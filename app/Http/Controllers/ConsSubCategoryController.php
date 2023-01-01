<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsSubCategoryCreateRequest;
use App\Http\Requests\ConsSubCategoryEditRequest;
use App\Models\ConsCategory;
use App\Models\ConsItem;
use App\Models\ConsSubCategory;
use App\Models\Unit;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class ConsSubCategoryController extends Controller
{
    public function index($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumable-items.sub-categories.index', [
            'sub_categories' => ConsSubCategory::orderBy('id')->get(),
            'unit' => $unitData
        ]);
    }

    public function create($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumable-items.sub-categories.create', [
            'categories' => ConsCategory::orderBy('category_name')->get(),
            'unit' => $unitData
        ]);
    }

    public function store($unit, ConsSubCategoryCreateRequest $request)
    {
        $validateData = $request->validated();

        ConsSubCategory::create($validateData);
        return redirect()->route('consumable-items.sub-categories', $unit)->withSuccess('Data Sub Kategori Berhasil Ditambahkan');
    }

    public function show($unit, ConsSubCategory $sub_category)
    {
        //
    }

    public function edit($unit, ConsSubCategory $sub_category)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumable-items.sub-categories.edit', [
            'sub_category' => $sub_category,
            'categories' => ConsCategory::orderBy('category_name')->get(),
            'unit' => $unitData
        ]);
    }

    public function update($unit, ConsSubCategoryEditRequest $request, ConsSubCategory $sub_category)
    {
        $validateData = $request->validated();

        $sub_category->update($validateData);
        return redirect()->route('consumable-items.sub-categories', $unit)->withSuccess('Data Sub Kategori Berhasil Diubah');
    }

    public function delete($unit, ConsSubCategory $sub_category)
    {
        $item = ConsItem::where('cons_sub_category_id', $sub_category->id)->get();
        if ($item->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Barang');
        }

        $sub_category->delete();
        return redirect()->route('consumable-items.sub-categories', $unit)->withSuccess('Data Sub Kategori Berhasil Dihapus');
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(ConsSubCategory::class, 'sub_category_slug', $request->name);
        return response()->json(['sub_category_slug' => $slug]);
    }
}