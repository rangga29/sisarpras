<?php

namespace App\Http\Controllers;

use App\Http\Requests\NonConsCategoryCreateRequest;
use App\Http\Requests\NonConsCategoryEditRequest;
use App\Models\NonConsCategory;
use App\Models\NonConsSubCategory;
use App\Models\Unit;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class NonConsCategoryController extends Controller
{
    public function index($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.categories.index', [
            'categories' => NonConsCategory::orderBy('category_code')->get(),
            'unit' => $unitData
        ]);
    }

    public function create($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.categories.create', [
            'unit' => $unitData
        ]);
    }

    public function store($unit, NonConsCategoryCreateRequest $request)
    {
        $validateData = $request->validated();
        NonConsCategory::create($validateData);
        return redirect()->route('non-consumable-items.categories', $unit)->withSuccess('Data Kategori Berhasil Ditambahkan');
    }

    public function edit($unit, NonConsCategory $category)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.categories.edit', [
            'category' => $category,
            'unit' => $unitData
        ]);
    }

    public function update($unit, NonConsCategoryEditRequest $request, NonConsCategory $category)
    {
        $validateData = $request->validated();
        $category->update($validateData);
        return redirect()->route('non-consumable-items.categories', $unit)->withSuccess('Data Kategori Berhasil Diubah');
    }

    public function delete($unit, NonConsCategory $category)
    {
        $item = NonConsSubCategory::where('non_cons_category_id', $category->id)->get();
        if ($item->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Sub Kategori');
        }

        $category->delete();
        return redirect()->route('non-consumable-items.categories', $unit)->withSuccess('Data Kategori Berhasil Dihapus');
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(NonConsCategory::class, 'category_slug', $request->name);
        return response()->json(['category_slug' => $slug]);
    }
}