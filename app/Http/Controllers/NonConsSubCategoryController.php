<?php

namespace App\Http\Controllers;

use App\Http\Requests\NonConsSubCategoryCreateRequest;
use App\Http\Requests\NonConsSubCategoryEditRequest;
use App\Models\NonConsCategory;
use App\Models\NonConsItem;
use App\Models\NonConsSubCategory;
use App\Models\Unit;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class NonConsSubCategoryController extends Controller
{
    public function index($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.sub-categories.index', [
            'sub_categories' => NonConsSubCategory::orderBy('non_cons_category_id')->orderBy('sub_category_code')->get(),
            'unit' => $unitData
        ]);
    }

    public function create($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.sub-categories.create', [
            'categories' => NonConsCategory::orderBy('category_code')->get(),
            'unit' => $unitData
        ]);
    }

    public function store($unit, NonConsSubCategoryCreateRequest $request)
    {
        $validateData = $request->validated();

        $sub_categories = NonConsSubCategory::where('non_cons_category_id', $validateData['non_cons_category_id'])->get();
        foreach ($sub_categories as $sub_category) {
            if ($sub_category->sub_category_code == $validateData['sub_category_code']) {
                return back()->withWarning('Kode Sub Kategori Sudah Digunakan Pada Kategori Yang Dipilih');
            }
        }

        NonConsSubCategory::create($validateData);
        return redirect()->route('non-consumable-items.sub-categories', $unit)->withSuccess('Data Sub Kategori Berhasil Ditambahkan');
    }

    public function edit($unit, NonConsSubCategory $sub_category)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.sub-categories.edit', [
            'sub_category' => $sub_category,
            'categories' => NonConsCategory::orderBy('category_code')->get(),
            'unit' => $unitData
        ]);
    }

    public function update($unit, NonConsSubCategoryEditRequest $request, NonConsSubCategory $sub_category)
    {
        $validateData = $request->validated();

        if ($validateData['non_cons_category_id'] == $request->old_non_cons_category_id) {
            if ($validateData['sub_category_code'] != $request->old_sub_category_code) {
                $sub_categories = NonConsSubCategory::where('non_cons_category_id', $validateData['non_cons_category_id'])->get();
                foreach ($sub_categories as $sub_category) {
                    if ($sub_category->sub_category_code == $validateData['sub_category_code']) {
                        return back()->withWarning('Kode Sub Kategori Sudah Digunakan Pada Kategori Yang Dipilih');
                    }
                }
            }
        } else {
            $sub_categories = NonConsSubCategory::where('non_cons_category_id', $validateData['non_cons_category_id'])->get();
            foreach ($sub_categories as $sub_category) {
                if ($sub_category->sub_category_code == $validateData['sub_category_code']) {
                    return back()->withWarning('Kode Sub Kategori Sudah Digunakan Pada Kategori Yang Dipilih');
                }
            }
        }

        $sub_category->update($validateData);
        return redirect()->route('non-consumable-items.sub-categories', $unit)->withSuccess('Data Sub Kategori Berhasil Diubah');
    }

    public function delete($unit, NonConsSubCategory $sub_category)
    {
        $item = NonConsItem::where('non_cons_sub_category_id', $sub_category->id)->get();
        if ($item->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Barang');
        }

        $sub_category->delete();
        return redirect()->route('non-consumable-items.sub-categories', $unit)->withSuccess('Data Sub Kategori Berhasil Dihapus');
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(NonConsSubCategory::class, 'sub_category_slug', $request->name);
        return response()->json(['sub_category_slug' => $slug]);
    }
}