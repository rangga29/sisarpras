<?php

namespace App\Http\Controllers;

use App\Exports\PickupItemExport;
use App\Models\Unit;
use App\Models\ConsItem;
use App\Models\Consumer;
use App\Models\PickupItem;
use Illuminate\Support\Str;
use App\Http\Requests\PickupItemCreateRequest;
use App\Http\Requests\PickupItemEditRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;

class PickupItemController extends Controller
{
    public function index($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumable-items.pickup.index', [
            'pickups' => PickupItem::orderBy('pickup_date', 'desc')->get(),
            'unit' => $unitData
        ]);
    }

    public function create($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumable-items.pickup.create', [
            'items' => ConsItem::join('cons_sub_categories', 'cons_sub_categories.id', '=', 'cons_items.cons_sub_category_id')
                ->join('cons_categories', 'cons_categories.id', '=', 'cons_sub_categories.cons_category_id')
                ->where('cons_items.unit_id', $unitData->id)
                ->where('cons_items.stock_amount', '!=', 0)
                ->orderBy('cons_categories.category_name', 'asc')
                ->orderBy('cons_sub_categories.sub_category_name', 'asc')
                ->orderBy('cons_items.name', 'asc')
                ->get([
                    'cons_categories.id as c_id',
                    'cons_categories.category_name as c_name',
                    'cons_sub_categories.id as sc_id',
                    'cons_sub_categories.sub_category_name as sc_name',
                    'cons_items.id as i_id',
                    'cons_items.name as i_name'
                ]),
            'consumers' => Consumer::where('unit_id', $unitData->id)->orderBy('name', 'asc')->get(),
            'unit' => $unitData
        ]);
    }

    public function store($unit, PickupItemCreateRequest $request)
    {
        $validateData = $request->validated();

        $cons_item = ConsItem::firstWhere('id', $validateData['cons_item_id']);
        if ($validateData['amount'] > $cons_item->stock_amount) {
            return back()->withWarning('Jumlah Ambil Melebihi Stok Barang');
        }

        $cons_item->stock_amount = $cons_item->stock_amount - $validateData['amount'];
        $cons_item->taken_amount = $cons_item->taken_amount + $validateData['amount'];
        $cons_item->save();

        $validateData['unit_id'] = Unit::where('slug', $unit)->first()->id;
        $validateData['pickup_code'] = Str::random(10);
        $validateData['pickup_date'] = Carbon::createFromFormat('d/m/Y', $validateData['pickup_date'])->format('Y-m-d');

        PickupItem::create($validateData);
        return redirect()->route('consumable-items.pickup-items', $unit)->withSuccess('Data Pengambilan Berhasil Ditambahkan');
    }

    public function edit($unit, PickupItem $pickup)
    {
        $unitData = Unit::where('slug', $unit)->first();
        $pickup_date = Carbon::createFromFormat('Y-m-d', $pickup->pickup_date)->format('d/m/Y');
        return view('consumable-items.pickup.edit', [
            'pickup' => $pickup,
            'pickup_date' => $pickup_date,
            'items' => ConsItem::join('cons_sub_categories', 'cons_sub_categories.id', '=', 'cons_items.cons_sub_category_id')
                ->join('cons_categories', 'cons_categories.id', '=', 'cons_sub_categories.cons_category_id')
                ->where('cons_items.unit_id', $unitData->id)
                ->where('cons_items.stock_amount', '!=', 0)
                ->orderBy('cons_categories.category_name', 'asc')
                ->orderBy('cons_sub_categories.sub_category_name', 'asc')
                ->orderBy('cons_items.name', 'asc')
                ->get([
                    'cons_categories.id as c_id',
                    'cons_categories.category_name as c_name',
                    'cons_sub_categories.id as sc_id',
                    'cons_sub_categories.sub_category_name as sc_name',
                    'cons_items.id as i_id',
                    'cons_items.name as i_name'
                ]),
            'consumers' => Consumer::where('unit_id', $unitData->id)->orderBy('name', 'asc')->get(),
            'unit' => $unitData
        ]);
    }

    public function update($unit, PickupItemEditRequest $request, PickupItem $pickup)
    {
        $validateData = $request->validated();

        $old_data = ConsItem::firstWhere('id', $pickup->cons_item_id);
        $new_data = ConsItem::firstWhere('id', $validateData['cons_item_id']);

        if ($old_data->id == $new_data->id) {
            if ($pickup->amount > $validateData['amount']) {
                $diff = $pickup->amount - $validateData['amount'];
                $new_data->stock_amount = $new_data->stock_amount + $diff;
                $new_data->taken_amount = $new_data->taken_amount - $diff;
            } elseif ($pickup->amount < $validateData['amount']) {
                $diff = $validateData['amount'] - $pickup->amount;
                $new_data->stock_amount = $new_data->stock_amount - $diff;
                $new_data->taken_amount = $new_data->taken_amount + $diff;
            }

            if ($new_data->stock_amount < 0) {
                return back()->withWarning('Jumlah Ambil Melebihi Stok Barang');
            }
            $new_data->save();
        } else {
            if ($pickup->amount == $validateData['amount']) {
                $old_data->stock_amount = $old_data->stock_amount + $validateData['amount'];
                $old_data->taken_amount = $old_data->taken_amount - $validateData['amount'];
                $new_data->stock_amount = $new_data->stock_amount - $validateData['amount'];
                $new_data->taken_amount = $new_data->taken_amount + $validateData['amount'];
            } else {
                $old_data->stock_amount = $old_data->stock_amount + $pickup->amount;
                $old_data->taken_amount = $old_data->taken_amount - $pickup->amount;
                $new_data->stock_amount = $new_data->stock_amount - $validateData['amount'];
                $new_data->taken_amount = $new_data->taken_amount + $validateData['amount'];
            }
            if ($new_data->stock_amount < 0 || $old_data->stock_amount < 0) {
                return back()->withWarning('Jumlah Ambil Melebihi Stok Barang');
            }
            $old_data->save();
            $new_data->save();
        }

        $validateData['unit_id'] = Unit::where('slug', $unit)->first()->id;
        $validateData['pickup_date'] = Carbon::createFromFormat('d/m/Y', $validateData['pickup_date'])->format('Y-m-d');
        $pickup->update($validateData);
        return redirect()->route('consumable-items.pickup-items', $unit)->withSuccess('Data Pengambilan Berhasil Diubah');
    }

    public function delete($unit, PickupItem $pickup)
    {
        $cons_item = ConsItem::firstWhere('id', $pickup->cons_item_id);
        $cons_item->stock_amount = $cons_item->stock_amount + $pickup->amount;
        $cons_item->taken_amount = $cons_item->taken_amount - $pickup->amount;
        $cons_item->save();

        $pickup->delete();
        return redirect()->route('consumable-items.pickup-items', $unit)->withSuccess('Data Pengambilan Berhasil Dihapus');
    }

    public function reportPdf($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumable-items.pickup.report-pdf', [
            'items' => ConsItem::join('cons_sub_categories', 'cons_sub_categories.id', '=', 'cons_items.cons_sub_category_id')
                ->join('cons_categories', 'cons_categories.id', '=', 'cons_sub_categories.cons_category_id')
                ->where('cons_items.unit_id', $unitData->id)
                ->where('cons_items.stock_amount', '!=', 0)
                ->orderBy('cons_categories.category_name', 'asc')
                ->orderBy('cons_sub_categories.sub_category_name', 'asc')
                ->orderBy('cons_items.name', 'asc')
                ->get([
                    'cons_categories.id as c_id',
                    'cons_categories.category_name as c_name',
                    'cons_sub_categories.id as sc_id',
                    'cons_sub_categories.sub_category_name as sc_name',
                    'cons_items.id as i_id',
                    'cons_items.name as i_name'
                ]),
            'unit' => $unitData
        ]);
    }

    public function printPdf($unit, Request $request, $type)
    {
        $request->name ? $name = '1' : $name = '0';
        $request->category ? $category = '1' : $category = '0';
        $request->sub_category ? $sub_category = '1' : $sub_category = '0';
        $request->consumer ? $consumer = '1' : $consumer = '0';
        $request->unit_data ? $unit_data = '1' : $unit_data = '0';
        $request->date ? $date = '1' : $date = '0';
        $request->amount ? $amount = '1' : $amount = '0';
        $request->description ? $description = '1' : $description = '0';

        $unitData = Unit::where('slug', $unit)->first();
        if ($type == 'separate') {
            $first_date = Carbon::createFromFormat('d/m/Y', $request->firstDate)->format('Y-m-d');
            $last_date = Carbon::createFromFormat('d/m/Y', $request->lastDate)->format('Y-m-d');
        }

        if ($type == 'all') {
            if ($request->all_item_name != '') {
                $itemName = ConsItem::where('id', $request->all_item_name)->first()->name;
                $items = PickupItem::join('cons_items', 'pickup_items.cons_item_id', '=', 'cons_items.id')
                    ->join('cons_sub_categories', 'cons_items.cons_sub_category_id', '=', 'cons_sub_categories.id')
                    ->join('cons_categories', 'cons_sub_categories.cons_category_id', '=', 'cons_categories.id')
                    ->where('cons_items.id', $request->all_item_name)
                    ->where('pickup_items.unit_id', $unitData->id)
                    ->orderBy('pickup_items.pickup_date', 'asc')
                    ->orderBy('pickup_items.consumer_id', 'asc')
                    ->get([
                        'pickup_items.*',
                        'cons_categories.id as c_id',
                        'cons_categories.category_name as c_name',
                        'cons_sub_categories.id as sc_id',
                        'cons_sub_categories.sub_category_name as sc_name',
                        'cons_items.id as i_id',
                        'cons_items.name as i_name'
                    ]);
            } else {
                $itemName = '';
                $items = PickupItem::join('cons_items', 'pickup_items.cons_item_id', '=', 'cons_items.id')
                    ->join('cons_sub_categories', 'cons_items.cons_sub_category_id', '=', 'cons_sub_categories.id')
                    ->join('cons_categories', 'cons_sub_categories.cons_category_id', '=', 'cons_categories.id')
                    ->where('pickup_items.unit_id', $unitData->id)
                    ->orderBy('pickup_items.pickup_date', 'asc')
                    ->orderBy('pickup_items.consumer_id', 'asc')
                    ->get([
                        'pickup_items.*',
                        'cons_categories.id as c_id',
                        'cons_categories.category_name as c_name',
                        'cons_sub_categories.id as sc_id',
                        'cons_sub_categories.sub_category_name as sc_name',
                        'cons_items.id as i_id',
                        'cons_items.name as i_name'
                    ]);
            }
        } else {
            if ($request->separate_item_name != '') {
                $itemName = ConsItem::where('id', $request->separate_item_name)->first()->name;
                $items = PickupItem::join('cons_items', 'pickup_items.cons_item_id', '=', 'cons_items.id')
                    ->join('cons_sub_categories', 'cons_items.cons_sub_category_id', '=', 'cons_sub_categories.id')
                    ->join('cons_categories', 'cons_sub_categories.cons_category_id', '=', 'cons_categories.id')
                    ->where('cons_items.id', $request->separate_item_name)
                    ->where('pickup_items.unit_id', $unitData->id)
                    ->whereBetween('pickup_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                    ->orderBy('pickup_items.pickup_date', 'asc')
                    ->orderBy('pickup_items.consumer_id', 'asc')
                    ->get([
                        'pickup_items.*',
                        'cons_categories.id as c_id',
                        'cons_categories.category_name as c_name',
                        'cons_sub_categories.id as sc_id',
                        'cons_sub_categories.sub_category_name as sc_name',
                        'cons_items.id as i_id',
                        'cons_items.name as i_name'
                    ]);
            } else {
                $itemName = '';
                $items = PickupItem::join('cons_items', 'pickup_items.cons_item_id', '=', 'cons_items.id')
                    ->join('cons_sub_categories', 'cons_items.cons_sub_category_id', '=', 'cons_sub_categories.id')
                    ->join('cons_categories', 'cons_sub_categories.cons_category_id', '=', 'cons_categories.id')
                    ->where('pickup_items.unit_id', $unitData->id)
                    ->whereBetween('pickup_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                    ->orderBy('pickup_items.pickup_date', 'asc')
                    ->orderBy('pickup_items.consumer_id', 'asc')
                    ->get([
                        'pickup_items.*',
                        'cons_categories.id as c_id',
                        'cons_categories.category_name as c_name',
                        'cons_sub_categories.id as sc_id',
                        'cons_sub_categories.sub_category_name as sc_name',
                        'cons_items.id as i_id',
                        'cons_items.name as i_name'
                    ]);
            }
        }

        if ($items->isEmpty()) {
            return back()->withWarning('Data Yang Diminta Tidak Ditemukan');
        }

        $todayDate = Carbon::now()->format('Ymd');
        $todayDateConvert = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->isoFormat('DD MMMM Y');
        if ($type == 'all') {
            $firstDate = Carbon::createFromFormat('Y-m-d', $items->first()->pickup_date)->isoFormat('DD MMMM Y');
            $lastDate = Carbon::createFromFormat('Y-m-d', $items->last()->pickup_date)->isoFormat('DD MMMM Y');
        } else {
            $firstDate = Carbon::createFromFormat('Y-m-d', $first_date)->isoFormat('DD MMMM Y');
            $lastDate = Carbon::createFromFormat('Y-m-d', $last_date)->isoFormat('DD MMMM Y');
        }

        if ($itemName != '') {
            if ($firstDate == $lastDate) {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Pengambilan Barang [' . $itemName . '] (' . $firstDate . ').pdf';
            } else {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Pengambilan Barang [' . $itemName . '] (' . $firstDate . ' - ' . $lastDate . ').pdf';
            }
        } else {
            if ($firstDate == $lastDate) {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Pengambilan Barang (' . $firstDate . ').pdf';
            } else {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Pengambilan Barang (' . $firstDate . ' - ' . $lastDate . ').pdf';
            }
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadview('consumable-items.pickup.print-pdf', [
            'unitData' => $unitData,
            'title' => 'Laporan Data Pengambilan Barang',
            'subTitle1' => ($itemName != '' ? 'Barang : ' . $itemName : ''),
            'subTitle2' => '',
            'filter' => '',
            'items' => $items,
            'firstDate' => $firstDate,
            'lastDate' => $lastDate,
            'todayDate' => $todayDateConvert,
            'name' => $name,
            'category' => $category,
            'sub_category' => $sub_category,
            'consumer' => $consumer,
            'unit' => $unit_data,
            'date' => $date,
            'amount' => $amount,
            'description' => $description
        ])->setPaper('a4', 'landscape');
        return $pdf->stream($file_name);
    }

    public function reportExcel($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumable-items.pickup.report-excel', [
            'items' => ConsItem::join('cons_sub_categories', 'cons_sub_categories.id', '=', 'cons_items.cons_sub_category_id')
                ->join('cons_categories', 'cons_categories.id', '=', 'cons_sub_categories.cons_category_id')
                ->where('cons_items.unit_id', $unitData->id)
                ->where('cons_items.stock_amount', '!=', 0)
                ->orderBy('cons_categories.category_name', 'asc')
                ->orderBy('cons_sub_categories.sub_category_name', 'asc')
                ->orderBy('cons_items.name', 'asc')
                ->get([
                    'cons_categories.id as c_id',
                    'cons_categories.category_name as c_name',
                    'cons_sub_categories.id as sc_id',
                    'cons_sub_categories.sub_category_name as sc_name',
                    'cons_items.id as i_id',
                    'cons_items.name as i_name'
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
            $consumer = '1';
            $unit_data = '1';
            $date = '1';
            $amount = '1';
            $description = '1';
        } else {
            $request->name ? $name = '1' : $name = '0';
            $request->category ? $category = '1' : $category = '0';
            $request->sub_category ? $sub_category = '1' : $sub_category = '0';
            $request->consumer ? $consumer = '1' : $consumer = '0';
            $request->unit_data ? $unit_data = '1' : $unit_data = '0';
            $request->date ? $date = '1' : $date = '0';
            $request->amount ? $amount = '1' : $amount = '0';
            $request->description ? $description = '1' : $description = '0';
        }

        $unitData = Unit::where('slug', $unit)->first();
        if ($type == 'separate') {
            $first_date = Carbon::createFromFormat('d/m/Y', $request->firstDate)->format('Y-m-d');
            $last_date = Carbon::createFromFormat('d/m/Y', $request->lastDate)->format('Y-m-d');
        }

        if ($type == 'all') {
            if ($request->all_item_name != '') {
                $itemName = ConsItem::where('id', $request->all_item_name)->first()->name;
                $items = PickupItem::join('cons_items', 'pickup_items.cons_item_id', '=', 'cons_items.id')
                    ->join('cons_sub_categories', 'cons_items.cons_sub_category_id', '=', 'cons_sub_categories.id')
                    ->join('cons_categories', 'cons_sub_categories.cons_category_id', '=', 'cons_categories.id')
                    ->where('cons_items.id', $request->all_item_name)
                    ->where('pickup_items.unit_id', $unitData->id)
                    ->orderBy('pickup_items.pickup_date', 'asc')
                    ->orderBy('pickup_items.consumer_id', 'asc')
                    ->get([
                        'pickup_items.*',
                        'cons_categories.id as c_id',
                        'cons_categories.category_name as c_name',
                        'cons_sub_categories.id as sc_id',
                        'cons_sub_categories.sub_category_name as sc_name',
                        'cons_items.id as i_id',
                        'cons_items.name as i_name'
                    ]);
            } else {
                $itemName = '';
                $items = PickupItem::join('cons_items', 'pickup_items.cons_item_id', '=', 'cons_items.id')
                    ->join('cons_sub_categories', 'cons_items.cons_sub_category_id', '=', 'cons_sub_categories.id')
                    ->join('cons_categories', 'cons_sub_categories.cons_category_id', '=', 'cons_categories.id')
                    ->where('pickup_items.unit_id', $unitData->id)
                    ->orderBy('pickup_items.pickup_date', 'asc')
                    ->orderBy('pickup_items.consumer_id', 'asc')
                    ->get([
                        'pickup_items.*',
                        'cons_categories.id as c_id',
                        'cons_categories.category_name as c_name',
                        'cons_sub_categories.id as sc_id',
                        'cons_sub_categories.sub_category_name as sc_name',
                        'cons_items.id as i_id',
                        'cons_items.name as i_name'
                    ]);
            }
        } else {
            if ($request->separate_item_name != '') {
                $itemName = ConsItem::where('id', $request->separate_item_name)->first()->name;
                $items = PickupItem::join('cons_items', 'pickup_items.cons_item_id', '=', 'cons_items.id')
                    ->join('cons_sub_categories', 'cons_items.cons_sub_category_id', '=', 'cons_sub_categories.id')
                    ->join('cons_categories', 'cons_sub_categories.cons_category_id', '=', 'cons_categories.id')
                    ->where('cons_items.id', $request->separate_item_name)
                    ->where('pickup_items.unit_id', $unitData->id)
                    ->whereBetween('pickup_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                    ->orderBy('pickup_items.pickup_date', 'asc')
                    ->orderBy('pickup_items.consumer_id', 'asc')
                    ->get([
                        'pickup_items.*',
                        'cons_categories.id as c_id',
                        'cons_categories.category_name as c_name',
                        'cons_sub_categories.id as sc_id',
                        'cons_sub_categories.sub_category_name as sc_name',
                        'cons_items.id as i_id',
                        'cons_items.name as i_name'
                    ]);
            } else {
                $itemName = '';
                $items = PickupItem::join('cons_items', 'pickup_items.cons_item_id', '=', 'cons_items.id')
                    ->join('cons_sub_categories', 'cons_items.cons_sub_category_id', '=', 'cons_sub_categories.id')
                    ->join('cons_categories', 'cons_sub_categories.cons_category_id', '=', 'cons_categories.id')
                    ->where('pickup_items.unit_id', $unitData->id)
                    ->whereBetween('pickup_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                    ->orderBy('pickup_items.pickup_date', 'asc')
                    ->orderBy('pickup_items.consumer_id', 'asc')
                    ->get([
                        'pickup_items.*',
                        'cons_categories.id as c_id',
                        'cons_categories.category_name as c_name',
                        'cons_sub_categories.id as sc_id',
                        'cons_sub_categories.sub_category_name as sc_name',
                        'cons_items.id as i_id',
                        'cons_items.name as i_name'
                    ]);
            }
        }

        if ($items->isEmpty()) {
            return back()->withWarning('Data Yang Diminta Tidak Ditemukan');
        }

        $todayDate = Carbon::now()->format('Ymd');
        $todayDateConvert = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->isoFormat('DD MMMM Y');
        if ($type == 'all') {
            $firstDate = Carbon::createFromFormat('Y-m-d', $items->first()->pickup_date)->isoFormat('DD MMMM Y');
            $lastDate = Carbon::createFromFormat('Y-m-d', $items->last()->pickup_date)->isoFormat('DD MMMM Y');
        } else {
            $firstDate = Carbon::createFromFormat('Y-m-d', $first_date)->isoFormat('DD MMMM Y');
            $lastDate = Carbon::createFromFormat('Y-m-d', $last_date)->isoFormat('DD MMMM Y');
        }

        if ($itemName != '') {
            if ($firstDate == $lastDate) {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Pengambilan Barang [' . $itemName . '] (' . $firstDate . ').xlsx';
            } else {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Pengambilan Barang [' . $itemName . '] (' . $firstDate . ' - ' . $lastDate . ').xlsx';
            }
        } else {
            if ($firstDate == $lastDate) {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Pengambilan Barang (' . $firstDate . ').xlsx';
            } else {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Pengambilan Barang (' . $firstDate . ' - ' . $lastDate . ').xlsx';
            }
        }

        return Excel::download(new PickupItemExport(
            $items,
            $name,
            $category,
            $sub_category,
            $consumer,
            $unit_data,
            $date,
            $amount,
            $description
        ), $file_name);
    }
}