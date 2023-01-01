<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomCreateRequest;
use App\Http\Requests\RoomEditRequest;
use App\Models\ConsItem;
use App\Models\NonConsItem;
use App\Models\PlacementItem;
use App\Models\Room;
use App\Models\Unit;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('rooms.index', [
            'rooms' => Room::where('unit_id', $unitData->id)->orderBy('id')->get(),
            'unit' => $unitData
        ]);
    }

    public function create($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('rooms.create', [
            'unit' => $unitData
        ]);
    }

    public function store($unit, RoomCreateRequest $request)
    {
        $validateData = $request->validated();
        Room::create($validateData);
        return redirect()->route('rooms', $unit)->withSuccess('Data Ruangan Berhasil Ditambahkan');
    }

    public function show(Room $room)
    {
        //
    }

    public function edit($unit, Room $room)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('rooms.edit', [
            'room' => $room,
            'unit' => $unitData
        ]);
    }

    public function update($unit, RoomEditRequest $request, Room $room)
    {
        $validateData = $request->validated();
        $room->update($validateData);
        return redirect()->route('rooms', $unit)->withSuccess('Data Ruangan Berhasil Diubah');
    }

    public function delete($unit, Room $room)
    {
        $cons_item = ConsItem::where('room_id', $room->id)->get();
        if ($cons_item->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Barang Habis Pakai');
        }

        $non_cons_item = NonConsItem::where('room_id', $room->id)->get();
        if ($non_cons_item->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Barang Tidak Habis Pakai');
        }

        $placement = PlacementItem::where('room_id', $room->id)->get();
        if ($placement->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Penempatan');
        }

        $room->delete();
        return redirect()->route('rooms', $unit)->withSuccess('Data Ruangan Berhasil Dihapus');
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(Room::class, 'slug', $request->name);
        return response()->json(['slug' => $slug]);
    }
}