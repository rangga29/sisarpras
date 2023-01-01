<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionCreateRequest;
use App\Http\Requests\PositionEditRequest;
use App\Models\Consumer;
use App\Models\Position;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        return view('positions.index', [
            'positions' => Position::orderBy('id')->get()
        ]);
    }

    public function create()
    {
        return view('positions.create');
    }

    public function store(PositionCreateRequest $request)
    {
        $validateData = $request->validated();
        Position::create($validateData);
        return redirect()->route('positions')->withSuccess('Data Posisi Berhasil Ditambahkan');
    }

    public function show(Position $position)
    {
        //
    }

    public function edit(Position $position)
    {
        return view('positions.edit', [
            'position' => $position
        ]);
    }

    public function update(PositionEditRequest $request, Position $position)
    {
        $validateData = $request->validated();
        $position->update($validateData);
        return redirect()->route('positions')->withSuccess('Data Posisi Berhasil Diubah');
    }

    public function delete(Position $position)
    {
        $items = Consumer::where('position_id', $position->id)->get();
        if ($items->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Pengguna');
        }

        $position->delete();
        return redirect()->route('positions')->withSuccess('Data Posisi Berhasil Dihapus');
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(Position::class, 'slug', $request->name);
        return response()->json(['slug' => $slug]);
    }
}