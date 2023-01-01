<?php

namespace App\Http\Controllers;

use App\Exports\PlacementItemExport;
use App\Models\Room;
use App\Models\Unit;
use App\Models\NonConsItem;
use Illuminate\Support\Str;
use App\Models\PlacementItem;
use App\Http\Requests\PlacementItemEditRequest;
use App\Http\Requests\PlacementItemCreateRequest;
use App\Http\Requests\ReturnPlacementItemCreateRequest;
use App\Http\Requests\ReturnPlacementItemEditRequest;
use App\Models\NonConsCondition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;

class PlacementItemController extends Controller
{
    public function index($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.placements.index', [
            'placements' => PlacementItem::whereNull('return_date')->orderBy('placement_date', 'desc')->get(),
            'return_placements' => PlacementItem::whereNotNull('return_date')->orderBy('placement_date', 'desc')->get(),
            'unit' => $unitData
        ]);
    }

    public function create($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.placements.create', [
            'items' => NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                ->where('non_cons_items.availability', 1)
                ->where('non_cons_items.non_cons_condition_id', 1)
                ->where('non_cons_items.unit_id', $unitData->id)
                ->orderBy('non_cons_categories.category_code', 'asc')
                ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                ->orderBy('non_cons_items.item_number', 'asc')
                ->orderBy('non_cons_items.name', 'asc')
                ->get([
                    'non_cons_categories.id as c_id',
                    'non_cons_categories.category_code as c_code',
                    'non_cons_categories.category_name as c_name',
                    'non_cons_sub_categories.id as sc_id',
                    'non_cons_sub_categories.sub_category_code as sc_code',
                    'non_cons_sub_categories.sub_category_name as sc_name',
                    'non_cons_items.id as i_id',
                    'non_cons_items.item_number as i_number',
                    'non_cons_items.name as i_name'
                ]),
            'rooms' => Room::where('unit_id', $unitData->id)->orderBy('name', 'asc')->get(),
            'unit' => $unitData
        ]);
    }

    public function store($unit, PlacementItemCreateRequest $request)
    {
        $validateData = $request->validated();

        $non_cons_item = NonConsItem::firstWhere('id', $validateData['non_cons_item_id']);
        $non_cons_item->availability = 0;
        $non_cons_item->save();

        $validateData['unit_id'] = Unit::where('slug', $unit)->first()->id;
        $validateData['placement_code'] = Str::random(10);
        $validateData['con_placement_id'] = $non_cons_item->non_cons_condition_id;
        $validateData['placement_date'] = Carbon::createFromFormat('d/m/Y', $validateData['placement_date'])->format('Y-m-d');

        PlacementItem::create($validateData);
        return redirect()->route('non-consumable-items.placement-items', $unit)->withSuccess('Data Penempatan Berhasil Ditambahkan');
    }

    public function edit($unit, PlacementItem $placement)
    {
        $unitData = Unit::where('slug', $unit)->first();
        $placement_date = Carbon::createFromFormat('Y-m-d', $placement->placement_date)->format('d/m/Y');
        return view('non-consumable-items.placements.edit', [
            'placement' => $placement,
            'placement_date' => $placement_date,
            'items' => NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                ->where('non_cons_items.availability', 1)
                ->where('non_cons_items.non_cons_condition_id', 1)
                ->orWhere('non_cons_items.id', $placement->non_cons_item->id)
                ->where('non_cons_items.unit_id', $unitData->id)
                ->orderBy('non_cons_categories.category_code', 'asc')
                ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                ->orderBy('non_cons_items.item_number', 'asc')
                ->orderBy('non_cons_items.name', 'asc')
                ->get([
                    'non_cons_categories.id as c_id',
                    'non_cons_categories.category_code as c_code',
                    'non_cons_categories.category_name as c_name',
                    'non_cons_sub_categories.id as sc_id',
                    'non_cons_sub_categories.sub_category_code as sc_code',
                    'non_cons_sub_categories.sub_category_name as sc_name',
                    'non_cons_items.id as i_id',
                    'non_cons_items.item_number as i_number',
                    'non_cons_items.name as i_name'
                ]),
            'rooms' => Room::where('unit_id', $unitData->id)->orderBy('name', 'asc')->get(),
            'unit' => $unitData
        ]);
    }

    public function update($unit, PlacementItemEditRequest $request, PlacementItem $placement)
    {
        $validateData = $request->validated();

        if ($validateData['non_cons_item_id'] != $placement->non_cons_item_id) {
            $old_data = NonConsItem::firstWhere('id', $placement->non_cons_item_id);
            $old_data->availability = 1;
            $old_data->save();

            $new_data = NonConsItem::firstWhere('id', $validateData['non_cons_item_id']);
            $new_data->availability = 0;
            $new_data->save();

            $validateData['con_placement_id'] = $new_data->non_cons_condition_id;
        }

        $validateData['placement_date'] = Carbon::createFromFormat('d/m/Y', $validateData['placement_date'])->format('Y-m-d');

        $placement->update($validateData);
        return redirect()->route('non-consumable-items.placement-items', $unit)->withSuccess('Data Penempatan Berhasil Diubah');
    }

    public function delete($unit, PlacementItem $placement)
    {
        $non_cons_item = NonConsItem::firstWhere('id', $placement->non_cons_item_id);
        if ($placement->return_date == null) {
            $non_cons_item->availability = 1;
            $non_cons_item->save();
        }

        $placement->delete();
        return redirect()->route('non-consumable-items.placement-items', $unit)->withSuccess('Data Penempatan Berhasil Dihapus');
    }

    public function createReturnItem($unit, PlacementItem $placement)
    {
        $unitData = Unit::where('slug', $unit)->first();
        $placement_date = Carbon::createFromFormat('Y-m-d', $placement->placement_date)->format('d/m/Y');
        return view('non-consumable-items.placements.create-return', [
            'placement' => $placement,
            'placement_date' => $placement_date,
            'items' => NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                ->where('non_cons_items.availability', 1)
                ->where('non_cons_items.non_cons_condition_id', 1)
                ->where('non_cons_items.unit_id', $unitData->id)
                ->orWhere('non_cons_items.id', $placement->non_cons_item->id)
                ->orderBy('non_cons_categories.category_code', 'asc')
                ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                ->orderBy('non_cons_items.item_number', 'asc')
                ->orderBy('non_cons_items.name', 'asc')
                ->get([
                    'non_cons_categories.id as c_id',
                    'non_cons_categories.category_code as c_code',
                    'non_cons_categories.category_name as c_name',
                    'non_cons_sub_categories.id as sc_id',
                    'non_cons_sub_categories.sub_category_code as sc_code',
                    'non_cons_sub_categories.sub_category_name as sc_name',
                    'non_cons_items.id as i_id',
                    'non_cons_items.item_number as i_number',
                    'non_cons_items.name as i_name'
                ]),
            'rooms' => Room::where('unit_id', $unitData->id)->orderBy('name', 'asc')->get(),
            'conditions' => NonConsCondition::orderBy('id', 'asc')->get(),
            'unit' => $unitData
        ]);
    }

    public function storeReturnItem($unit, PlacementItem $placement, ReturnPlacementItemCreateRequest $request)
    {
        $validateData = $request->validated();

        $non_cons_item = NonConsItem::firstWhere('id', $placement->non_cons_item_id);
        $non_cons_item->non_cons_condition_id = $validateData['con_return_id'];
        $non_cons_item->availability = 1;
        $non_cons_item->save();

        $validateData['return_date'] = Carbon::createFromFormat('d/m/Y', $validateData['return_date'])->format('Y-m-d');

        $placement->update($validateData);
        return redirect()->route('non-consumable-items.placement-items', $unit)->withSuccess('Data Pengembalian Berhasil Ditambah');
    }

    public function editReturnItem($unit, PlacementItem $placement)
    {
        $unitData = Unit::where('slug', $unit)->first();
        $placement_date = Carbon::createFromFormat('Y-m-d', $placement->placement_date)->format('d/m/Y');
        $return_date = Carbon::createFromFormat('Y-m-d', $placement->return_date)->format('d/m/Y');
        return view('non-consumable-items.placements.edit-return', [
            'placement' => $placement,
            'placement_date' => $placement_date,
            'return_date' => $return_date,
            'items' => NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                ->where('non_cons_items.availability', 1)
                ->where('non_cons_items.non_cons_condition_id', 1)
                ->where('non_cons_items.unit_id', $unitData->id)
                ->orWhere('non_cons_items.id', $placement->non_cons_item->id)
                ->orderBy('non_cons_categories.category_code', 'asc')
                ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                ->orderBy('non_cons_items.item_number', 'asc')
                ->orderBy('non_cons_items.name', 'asc')
                ->get([
                    'non_cons_categories.id as c_id',
                    'non_cons_categories.category_code as c_code',
                    'non_cons_categories.category_name as c_name',
                    'non_cons_sub_categories.id as sc_id',
                    'non_cons_sub_categories.sub_category_code as sc_code',
                    'non_cons_sub_categories.sub_category_name as sc_name',
                    'non_cons_items.id as i_id',
                    'non_cons_items.item_number as i_number',
                    'non_cons_items.name as i_name'
                ]),
            'rooms' => Room::where('unit_id', $unitData->id)->orderBy('name', 'asc')->get(),
            'conditions' => NonConsCondition::orderBy('id', 'asc')->get(),
            'unit' => $unitData
        ]);
    }

    public function updateReturnItem($unit, PlacementItem $placement, ReturnPlacementItemEditRequest $request)
    {
        $validateData = $request->validated();

        $non_cons_item = NonConsItem::firstWhere('id', $placement->non_cons_item_id);
        if ($placement->con_return_id != $validateData['con_return_id']) {
            if ($non_cons_item->availability == 0) {
                return back()->withWarning('Barang Sedang Dipinjam atau Ditempatkan');
            }
        }
        $non_cons_item->non_cons_condition_id = $validateData['con_return_id'];
        $non_cons_item->save();

        $validateData['return_date'] = Carbon::createFromFormat('d/m/Y', $validateData['return_date'])->format('Y-m-d');

        $placement->update($validateData);
        return redirect()->route('non-consumable-items.placement-items', $unit)->withSuccess('Data Pengembalian Berhasil Diubah');
    }

    public function reportPdf($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.placements.report-pdf', [
            'items' => NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                ->where('non_cons_items.unit_id', $unitData->id)
                ->orderBy('non_cons_categories.category_name', 'asc')
                ->orderBy('non_cons_sub_categories.sub_category_name', 'asc')
                ->orderBy('non_cons_items.name', 'asc')
                ->get([
                    'non_cons_categories.id as c_id',
                    'non_cons_categories.category_code as c_code',
                    'non_cons_categories.category_name as c_name',
                    'non_cons_sub_categories.id as sc_id',
                    'non_cons_sub_categories.sub_category_code as sc_code',
                    'non_cons_sub_categories.sub_category_name as sc_name',
                    'non_cons_items.id as i_id',
                    'non_cons_items.item_number as i_number',
                    'non_cons_items.name as i_name'
                ]),
            'unit' => $unitData
        ]);
    }

    public function printPdf($unit, Request $request, $type)
    {
        $request->name ? $name = '1' : $name = '0';
        $request->category ? $category = '1' : $category = '0';
        $request->sub_category ? $sub_category = '1' : $sub_category = '0';
        $request->room ? $room = '1' : $room = '0';
        $request->unit_data ? $unit_data = '1' : $unit_data = '0';
        $request->condition_placement ? $condition_placement = '1' : $condition_placement = '0';
        $request->condition_return ? $condition_return = '1' : $condition_return = '0';
        $request->placement_date ? $placement_date = '1' : $placement_date = '0';
        $request->return_date ? $return_date = '1' : $return_date = '0';
        $request->description ? $description = '1' : $description = '0';

        $unitData = Unit::where('slug', $unit)->first();
        if ($type == 'separate') {
            $first_date = Carbon::createFromFormat('d/m/Y', $request->firstDate)->format('Y-m-d');
            $last_date = Carbon::createFromFormat('d/m/Y', $request->lastDate)->format('Y-m-d');
        }

        if ($type == 'all') {
            if ($request->all_filter == 'filter-belum-kembali') {
                $filter = 'Belum Kembali';
                if ($request->all_item_name != '') {
                    $itemName = NonConsItem::where('id', $request->all_item_name)->first()->name;
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->all_item_name)
                        ->where('placement_items.return_date', '=', null)
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                } else {
                    $itemName = '';
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('placement_items.return_date', '=', null)
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                }
            } elseif ($request->all_filter == 'filter-sudah-kembali') {
                $filter = 'Sudah Kembali';
                if ($request->all_item_name != '') {
                    $itemName = NonConsItem::where('id', $request->all_item_name)->first()->name;
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->all_item_name)
                        ->where('placement_items.return_date', '!=', null)
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                } else {
                    $itemName = '';
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('placement_items.return_date', '!=', null)
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                }
            } else {
                $filter = '';
                if ($request->all_item_name != '') {
                    $itemName = NonConsItem::where('id', $request->all_item_name)->first()->name;
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->all_item_name)
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                } else {
                    $itemName = '';
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                }
            }
        } else {
            if ($request->separate_filter == 'filter-belum-kembali') {
                $filter = 'Belum Kembali';
                if ($request->separate_item_name != '') {
                    $itemName = NonConsItem::where('id', $request->separate_item_name)->first()->name;
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->separate_item_name)
                        ->where('placement_items.return_date', '=', null)
                        ->whereBetween('placement_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                } else {
                    $itemName = '';
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('placement_items.return_date', '=', null)
                        ->whereBetween('placement_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                }
            } elseif ($request->separate_filter == 'filter-sudah-kembali') {
                $filter = 'Sudah Kembali';
                if ($request->separate_item_name != '') {
                    $itemName = NonConsItem::where('id', $request->separate_item_name)->first()->name;
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->separate_item_name)
                        ->where('placement_items.return_date', '!=', null)
                        ->whereBetween('placement_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                } else {
                    $itemName = '';
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('placement_items.return_date', '!=', null)
                        ->whereBetween('placement_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                }
            } else {
                $filter = '';
                if ($request->separate_item_name != '') {
                    $itemName = NonConsItem::where('id', $request->separate_item_name)->first()->name;
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->separate_item_name)
                        ->whereBetween('placement_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                } else {
                    $itemName = '';
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->whereBetween('placement_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                }
            }
        }

        if ($items->isEmpty()) {
            return back()->withWarning('Data Yang Diminta Tidak Ditemukan');
        }

        $todayDate = Carbon::now()->format('Ymd');
        $todayDateConvert = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->isoFormat('DD MMMM Y');
        if ($type == 'all') {
            $firstDate = Carbon::createFromFormat('Y-m-d', $items->first()->placement_date)->isoFormat('DD MMMM Y');
            $lastDate = Carbon::createFromFormat('Y-m-d', $items->last()->placement_date)->isoFormat('DD MMMM Y');
        } else {
            $firstDate = Carbon::createFromFormat('Y-m-d', $first_date)->isoFormat('DD MMMM Y');
            $lastDate = Carbon::createFromFormat('Y-m-d', $last_date)->isoFormat('DD MMMM Y');
        }

        if ($itemName != '') {
            if ($filter != '') {
                if ($firstDate == $lastDate) {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Penempatan Barang [' . $itemName . ' - ' . $filter . '] (' . $firstDate . ').pdf';
                } else {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Penempatan Barang [' . $itemName . ' - ' . $filter . '] (' . $firstDate . ' - ' . $lastDate . ').pdf';
                }
            } else {
                if ($firstDate == $lastDate) {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Penempatan Barang [' . $itemName . '] (' . $firstDate . ').pdf';
                } else {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Penempatan Barang [' . $itemName . '] (' . $firstDate . ' - ' . $lastDate . ').pdf';
                }
            }
        } else {
            if ($filter != '') {
                if ($firstDate == $lastDate) {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Penempatan Barang [' . $filter . '] (' . $firstDate . ').pdf';
                } else {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Penempatan Barang [' . $filter . '] (' . $firstDate . ' - ' . $lastDate . ').pdf';
                }
            } else {
                if ($firstDate == $lastDate) {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Penempatan Barang (' . $firstDate . ').pdf';
                } else {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Penempatan Barang (' . $firstDate . ' - ' . $lastDate . ').pdf';
                }
            }
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadview('non-consumable-items.placements.print-pdf', [
            'unitData' => $unitData,
            'title' => 'Laporan Data Penempatan Barang',
            'subTitle1' => '',
            'subTitle2' => ($itemName != '' ? 'Barang : ' . $itemName : ''),
            'filter' => ($filter != '' ? 'Filter : ' . $filter : ''),
            'items' => $items,
            'firstDate' => $firstDate,
            'lastDate' => $lastDate,
            'todayDate' => $todayDateConvert,
            'name' => $name,
            'category' => $category,
            'sub_category' => $sub_category,
            'room' => $room,
            'unit' => $unit_data,
            'condition_placement' => $condition_placement,
            'condition_return' => $condition_return,
            'placement_date' => $placement_date,
            'return_date' => $return_date,
            'description' => $description
        ])->setPaper('a4', 'landscape');
        return $pdf->stream($file_name);
    }

    public function reportExcel($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.placements.report-excel', [
            'items' => NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                ->where('non_cons_items.unit_id', $unitData->id)
                ->orderBy('non_cons_categories.category_name', 'asc')
                ->orderBy('non_cons_sub_categories.sub_category_name', 'asc')
                ->orderBy('non_cons_items.name', 'asc')
                ->get([
                    'non_cons_categories.id as c_id',
                    'non_cons_categories.category_code as c_code',
                    'non_cons_categories.category_name as c_name',
                    'non_cons_sub_categories.id as sc_id',
                    'non_cons_sub_categories.sub_category_code as sc_code',
                    'non_cons_sub_categories.sub_category_name as sc_name',
                    'non_cons_items.id as i_id',
                    'non_cons_items.item_number as i_number',
                    'non_cons_items.name as i_name'
                ]),
            'unit' => $unitData
        ]);
    }

    public function printExcel($unit, Request $request, $type)
    {
        if ($request->all_column == '1') {
            $name = '1';
            $category = '1';
            $sub_category = '1';
            $room = '1';
            $unit_data = '1';
            $condition_placement = '1';
            $condition_return = '1';
            $placement_date = '1';
            $return_date = '1';
            $description = '1';
        } else {
            $request->name ? $name = '1' : $name = '0';
            $request->category ? $category = '1' : $category = '0';
            $request->sub_category ? $sub_category = '1' : $sub_category = '0';
            $request->room ? $room = '1' : $room = '0';
            $request->unit_data ? $unit_data = '1' : $unit_data = '0';
            $request->condition_placement ? $condition_placement = '1' : $condition_placement = '0';
            $request->condition_return ? $condition_return = '1' : $condition_return = '0';
            $request->placement_date ? $placement_date = '1' : $placement_date = '0';
            $request->return_date ? $return_date = '1' : $return_date = '0';
            $request->description ? $description = '1' : $description = '0';
        }

        $unitData = Unit::where('slug', $unit)->first();
        if ($type == 'separate') {
            $first_date = Carbon::createFromFormat('d/m/Y', $request->firstDate)->format('Y-m-d');
            $last_date = Carbon::createFromFormat('d/m/Y', $request->lastDate)->format('Y-m-d');
        }

        if ($type == 'all') {
            if ($request->all_filter == 'filter-belum-kembali') {
                $filter = 'Belum Kembali';
                if ($request->all_item_name != '') {
                    $itemName = NonConsItem::where('id', $request->all_item_name)->first()->name;
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->all_item_name)
                        ->where('placement_items.return_date', '=', null)
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                } else {
                    $itemName = '';
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('placement_items.return_date', '=', null)
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                }
            } elseif ($request->all_filter == 'filter-sudah-kembali') {
                $filter = 'Sudah Kembali';
                if ($request->all_item_name != '') {
                    $itemName = NonConsItem::where('id', $request->all_item_name)->first()->name;
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->all_item_name)
                        ->where('placement_items.return_date', '!=', null)
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                } else {
                    $itemName = '';
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('placement_items.return_date', '!=', null)
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                }
            } else {
                $filter = '';
                if ($request->all_item_name != '') {
                    $itemName = NonConsItem::where('id', $request->all_item_name)->first()->name;
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->all_item_name)
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                } else {
                    $itemName = '';
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                }
            }
        } else {
            if ($request->separate_filter == 'filter-belum-kembali') {
                $filter = 'Belum Kembali';
                if ($request->separate_item_name != '') {
                    $itemName = NonConsItem::where('id', $request->separate_item_name)->first()->name;
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->separate_item_name)
                        ->where('placement_items.return_date', '=', null)
                        ->whereBetween('placement_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                } else {
                    $itemName = '';
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('placement_items.return_date', '=', null)
                        ->whereBetween('placement_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                }
            } elseif ($request->separate_filter == 'filter-sudah-kembali') {
                $filter = 'Sudah Kembali';
                if ($request->separate_item_name != '') {
                    $itemName = NonConsItem::where('id', $request->separate_item_name)->first()->name;
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->separate_item_name)
                        ->where('placement_items.return_date', '!=', null)
                        ->whereBetween('placement_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                } else {
                    $itemName = '';
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('placement_items.return_date', '!=', null)
                        ->whereBetween('placement_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                }
            } else {
                $filter = '';
                if ($request->separate_item_name != '') {
                    $itemName = NonConsItem::where('id', $request->separate_item_name)->first()->name;
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->separate_item_name)
                        ->whereBetween('placement_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                } else {
                    $itemName = '';
                    $items = PlacementItem::join('non_cons_items', 'placement_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('placement_items.unit_id', $unitData->id)
                        ->whereBetween('placement_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('placement_items.placement_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'placement_items.*',
                            'non_cons_items.id as i_id',
                            'non_cons_items.item_number as i_number',
                            'non_cons_items.name as i_name',
                            'non_cons_sub_categories.id as sc_id',
                            'non_cons_sub_categories.sub_category_code as sc_code',
                            'non_cons_sub_categories.sub_category_name as sc_name',
                            'non_cons_categories.id as c_id',
                            'non_cons_categories.category_code as c_code',
                            'non_cons_categories.category_name as c_name'
                        ]);
                }
            }
        }

        if ($items->isEmpty()) {
            return back()->withWarning('Data Yang Diminta Tidak Ditemukan');
        }

        $todayDate = Carbon::now()->format('Ymd');
        $todayDateConvert = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->isoFormat('DD MMMM Y');
        if ($type == 'all') {
            $firstDate = Carbon::createFromFormat('Y-m-d', $items->first()->placement_date)->isoFormat('DD MMMM Y');
            $lastDate = Carbon::createFromFormat('Y-m-d', $items->last()->placement_date)->isoFormat('DD MMMM Y');
        } else {
            $firstDate = Carbon::createFromFormat('Y-m-d', $first_date)->isoFormat('DD MMMM Y');
            $lastDate = Carbon::createFromFormat('Y-m-d', $last_date)->isoFormat('DD MMMM Y');
        }

        if ($itemName != '') {
            if ($filter != '') {
                if ($firstDate == $lastDate) {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Penempatan Barang [' . $itemName . ' - ' . $filter . '] (' . $firstDate . ').xlsx';
                } else {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Penempatan Barang [' . $itemName . ' - ' . $filter . '] (' . $firstDate . ' - ' . $lastDate . ').xlsx';
                }
            } else {
                if ($firstDate == $lastDate) {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Penempatan Barang [' . $itemName . '] (' . $firstDate . ').xlsx';
                } else {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Penempatan Barang [' . $itemName . '] (' . $firstDate . ' - ' . $lastDate . ').xlsx';
                }
            }
        } else {
            if ($filter != '') {
                if ($firstDate == $lastDate) {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Penempatan Barang [' . $filter . '] (' . $firstDate . ').xlsx';
                } else {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Penempatan Barang [' . $filter . '] (' . $firstDate . ' - ' . $lastDate . ').xlsx';
                }
            } else {
                if ($firstDate == $lastDate) {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Penempatan Barang (' . $firstDate . ').xlsx';
                } else {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Penempatan Barang (' . $firstDate . ' - ' . $lastDate . ').xlsx';
                }
            }
        }

        return Excel::download(new PlacementItemExport(
            $items,
            $name,
            $category,
            $sub_category,
            $room,
            $unit_data,
            $condition_placement,
            $condition_return,
            $placement_date,
            $return_date,
            $description
        ), $file_name);
    }
}