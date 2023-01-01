<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsCategoryCreateRequest;
use App\Http\Requests\ConsCategoryEditRequest;
use App\Models\ConsCategory;
use App\Models\ConsSubCategory;
use App\Models\Unit;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class ConsCategoryController extends Controller
{
    public function index($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumable-items.categories.index', [
            'categories' => ConsCategory::orderBy('id')->get(),
            'unit' => $unitData
        ]);
    }

    public function create($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumable-items.categories.create', [
            'unit' => $unitData
        ]);
    }

    public function store($unit, ConsCategoryCreateRequest $request)
    {
        $validateData = $request->validated();
        ConsCategory::create($validateData);
        return redirect()->route('consumable-items.categories', $unit)->withSuccess('Data Kategori Berhasil Ditambahkan');
    }

    public function edit($unit, ConsCategory $category)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumable-items.categories.edit', [
            'category' => $category,
            'unit' => $unitData
        ]);
    }

    public function update($unit, ConsCategoryEditRequest $request, ConsCategory $category)
    {
        $validateData = $request->validated();
        $category->update($validateData);
        return redirect()->route('consumable-items.categories', $unit)->withSuccess('Data Kategori Berhasil Diubah');
    }

    public function delete($unit, ConsCategory $category)
    {
        $item = ConsSubCategory::where('cons_category_id', $category->id)->get();
        if ($item->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Sub Kategori');
        }

        $category->delete();
        return redirect()->route('consumable-items.categories', $unit)->withSuccess('Data Kategori Berhasil Dihapus');
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(ConsCategory::class, 'category_slug', $request->name);
        return response()->json(['category_slug' => $slug]);
    }
}