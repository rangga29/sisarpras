<?php

namespace App\Http\Controllers;

use App\Exports\ConsItemExport;
use Carbon\Carbon;
use App\Models\Fund;
use App\Models\Room;
use App\Models\Shop;
use App\Models\Unit;
use App\Models\Brand;
use App\Models\ConsItem;
use App\Models\PickupItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ConsSubCategory;
use Illuminate\Support\Facades\App;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ConsItemEditRequest;
use App\Http\Requests\ConsItemCreateRequest;
use Maatwebsite\Excel\Facades\Excel;

class ConsItemController extends Controller
{
    public function index($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumable-items.index', [
            'items' => ConsItem::where('unit_id', $unitData->id)->orderBy('purchase_date', 'desc')->get(),
            'items_stock' => ConsItem::where('stock_amount', 0)->where('unit_id', $unitData->id)->orderBy('purchase_date', 'desc')->get(),
            'unit' => $unitData
        ]);
    }

    public function create($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumable-items.create', [
            'sub_categories' => ConsSubCategory::join('cons_categories', 'cons_sub_categories.cons_category_id', '=', 'cons_categories.id')
                ->orderBy('cons_categories.category_name', 'asc')
                ->orderBy('cons_sub_categories.sub_category_name', 'asc')
                ->get([
                    'cons_categories.id as c_id',
                    'cons_categories.category_name as c_name',
                    'cons_sub_categories.id as sc_id',
                    'cons_sub_categories.sub_category_name as sc_name'
                ]),
            'brands' => Brand::orderBy('name', 'asc')->get(),
            'shops' => Shop::orderBy('name', 'asc')->get(),
            'funds' => Fund::orderBy('name', 'asc')->get(),
            'rooms' => Room::where('unit_id', $unitData->id)->orderBy('name', 'asc')->get(),
            'unit' => $unitData
        ]);
    }

    public function store($unit, ConsItemCreateRequest $request)
    {
        $validateData = $request->validated();

        $validateData['item_code'] = Str::random(10);
        $validateData['taken_amount'] = 0;
        $validateData['stock_amount'] = $validateData['initial_amount'];
        $validateData['purchase_date'] = Carbon::createFromFormat('d/m/Y', $request->purchase_date)->format('Y-m-d');

        $filenameImage = uniqid() . '.png';
        $imgData = Image::make($validateData['image'])->resize(320, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('png');
        Storage::put('public/consumable-items/' . $filenameImage, $imgData);
        $validateData['image'] = $filenameImage;

        $filenameReceipt = uniqid() . '.pdf';
        Storage::putFileAs('public/consumable-items/', $validateData['receipt'], $filenameReceipt);
        $validateData['receipt'] = $filenameReceipt;

        ConsItem::create($validateData);
        return redirect()->route('consumable-items', $unit)->withSuccess('Data Barang Berhasil Ditambahkan');
    }

    public function show($unit, ConsItem $item)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumable-items.show', [
            'item' => $item,
            'unit' => $unitData
        ]);
    }

    public function edit($unit, ConsItem $item)
    {
        $unitData = Unit::where('slug', $unit)->first();
        $purchase_date = Carbon::createFromFormat('Y-m-d', $item->purchase_date)->format('d/m/Y');
        return view('consumable-items.edit', [
            'item' => $item,
            'purchase_date' => $purchase_date,
            'sub_categories' => ConsSubCategory::join('cons_categories', 'cons_sub_categories.cons_category_id', '=', 'cons_categories.id')
                ->orderBy('cons_categories.category_name', 'asc')
                ->orderBy('cons_sub_categories.sub_category_name', 'asc')
                ->get([
                    'cons_categories.id as c_id',
                    'cons_categories.category_name as c_name',
                    'cons_sub_categories.id as sc_id',
                    'cons_sub_categories.sub_category_name as sc_name'
                ]),
            'brands' => Brand::orderBy('name', 'asc')->get(),
            'shops' => Shop::orderBy('name', 'asc')->get(),
            'funds' => Fund::orderBy('name', 'asc')->get(),
            'rooms' => Room::where('unit_id', $unitData->id)->orderBy('name', 'asc')->get(),
            'unit' => $unitData
        ]);
    }


    public function update($unit, ConsItemEditRequest $request, ConsItem $item)
    {
        $validateData = $request->validated();

        $stock_taken_amount = $validateData['stock_amount'] + $item->taken_amount;
        if ($validateData['initial_amount'] != $stock_taken_amount) {
            return back()->withWarning('Jumlah Awal Harus Sama Dengan Jumlah Diambil dan Jumlah Stok');
        }

        if ($request->image) {
            $filenameImage = uniqid() . '.png';
            $imgData = Image::make($validateData['image'])->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('png');
            Storage::put('public/consumable-items/' . $filenameImage, $imgData);
            Storage::delete('public/consumable-items/' . $item->image);
            $validateData['image'] = $filenameImage;
        }

        if ($request->receipt) {
            $filenameReceipt = uniqid() . '.pdf';
            Storage::putFileAs('public/consumable-items/', $validateData['receipt'], $filenameReceipt);
            Storage::delete('public/consumable-items/' . $item->receipt);
            $validateData['receipt'] = $filenameReceipt;
        }

        $validateData['purchase_date'] = Carbon::createFromFormat('d/m/Y', $request->purchase_date)->format('Y-m-d');

        $item->update($validateData);
        return redirect()->route('consumable-items', $unit)->withSuccess('Data Barang Berhasil Diubah');
    }

    public function delete($unit, ConsItem $item)
    {
        $pickup = PickupItem::where('cons_item_id', $item->id)->get();
        if ($pickup->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Pengambilan');
        }

        Storage::delete('public/consumable-items/' . $item->image);
        Storage::delete('public/consumable-items/' . $item->receipt);
        $item->delete();
        return redirect()->route('consumable-items', $unit)->withSuccess('Data Barang Berhasil Dihapus');
    }

    public function reportPdf($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumable-items.report-pdf', [
            'unit' => $unitData
        ]);
    }

    public function printPdf($unit, Request $request, $type)
    {
        $request->name ? $name = '1' : $name = '0';
        $request->category ? $category = '1' : $category = '0';
        $request->sub_category ? $sub_category = '1' : $sub_category = '0';
        $request->brand ? $brand = '1' : $brand = '0';
        $request->shop ? $shop = '1' : $shop = '0';
        $request->fund ? $fund = '1' : $fund = '0';
        $request->room ? $room = '1' : $room = '0';
        $request->unit_data ? $unit_data = '1' : $unit_data = '0';
        $request->price ? $price = '1' : $price = '0';
        $request->purchase_date ? $purchase_date = '1' : $purchase_date = '0';
        $request->initial_amount ? $initial_amount = '1' : $initial_amount = '0';
        $request->taken_amount ? $taken_amount = '1' : $taken_amount = '0';
        $request->stock_amount ? $stock_amount = '1' : $stock_amount = '0';
        $request->description ? $description = '1' : $description = '0';
        $request->insert_date ? $insert_date = '1' : $insert_date = '0';

        $unitData = Unit::where('slug', $unit)->first();
        if ($type == 'separate') {
            $first_date = Carbon::createFromFormat('d/m/Y', $request->firstDate)->format('Y-m-d');
            $last_date = Carbon::createFromFormat('d/m/Y', $request->lastDate)->format('Y-m-d');
        }

        if ($type == 'all') {
            if ($request->all_filter == 'filter-stok-ada') {
                $filter = 'Stok Ada';
                $items = ConsItem::where('unit_id', $unitData->id)->where('stock_amount', '>', 0)->orderBy('purchase_date', 'asc')->orderBy('name', 'asc')->get();
            } elseif ($request->all_filter == 'filter-stok-habis') {
                $filter = 'Stok Habis';
                $items = ConsItem::where('unit_id', $unitData->id)->where('stock_amount', '<=', 0)->orderBy('purchase_date', 'asc')->orderBy('name', 'asc')->get();
            } else {
                $filter = '';
                $items = ConsItem::where('unit_id', $unitData->id)->orderBy('purchase_date', 'asc')->orderBy('name', 'asc')->get();
            }
        } else {
            if ($request->separate_filter == 'filter-stok-ada') {
                $filter = 'Stok Ada';
                $items = ConsItem::where('unit_id', $unitData->id)->where('stock_amount', '>', 0)->whereBetween('purchase_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                    ->orderBy('purchase_date', 'asc')->orderBy('name', 'asc')->get();
            } elseif ($request->separate_filter == 'filter-stok-habis') {
                $filter = 'Stok Habis';
                $items = ConsItem::where('unit_id', $unitData->id)->where('stock_amount', '<=', 0)->whereBetween('purchase_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                    ->orderBy('purchase_date', 'asc')->orderBy('name', 'asc')->get();
            } else {
                $filter = '';
                $items = ConsItem::where('unit_id', $unitData->id)->orderBy('purchase_date', 'asc')->whereBetween('purchase_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                    ->orderBy('name', 'asc')->get();
            }
        }

        if ($items->isEmpty()) {
            return back()->withWarning('Data Yang Diminta Tidak Ditemukan');
        }

        $todayDate = Carbon::now()->format('Ymd');
        $todayDateConvert = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->isoFormat('DD MMMM Y');
        if ($type == 'all') {
            $firstDate = Carbon::createFromFormat('Y-m-d', $items->first()->purchase_date)->isoFormat('DD MMMM Y');
            $lastDate = Carbon::createFromFormat('Y-m-d', $items->last()->purchase_date)->isoFormat('DD MMMM Y');
        } else {
            $firstDate = Carbon::createFromFormat('Y-m-d', $first_date)->isoFormat('DD MMMM Y');
            $lastDate = Carbon::createFromFormat('Y-m-d', $last_date)->isoFormat('DD MMMM Y');
        }

        if ($filter != '') {
            if ($firstDate == $lastDate) {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Barang Habis Pakai [' . $filter . '] (' . $firstDate . ').pdf';
            } else {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Barang Habis Pakai [' . $filter . '] (' . $firstDate . ' - ' . $lastDate . ').pdf';
            }
        } else {
            if ($firstDate == $lastDate) {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Barang Habis Pakai (' . $firstDate . ').pdf';
            } else {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Barang Habis Pakai (' . $firstDate . ' - ' . $lastDate . ').pdf';
            }
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadview('consumable-items.print-pdf', [
            'unitData' => $unitData,
            'title' => 'Laporan Data Barang Habis Pakai',
            'subTitle1' => '',
            'subTitle2' => '',
            'filter' => ($filter != '' ? 'Filter : ' . $filter : ''),
            'items' => $items,
            'firstDate' => $firstDate,
            'lastDate' => $lastDate,
            'todayDate' => $todayDateConvert,
            'name' => $name,
            'category' => $category,
            'sub_category' => $sub_category,
            'brand' => $brand,
            'shop' => $shop,
            'fund' => $fund,
            'room' => $room,
            'unit' => $unit_data,
            'price' => $price,
            'purchase_date' => $purchase_date,
            'initial_amount' => $initial_amount,
            'taken_amount' => $taken_amount,
            'stock_amount' => $stock_amount,
            'description' => $description,
            'insert_date' => $insert_date
        ])->setPaper('a4', 'landscape');
        return $pdf->stream($file_name);
    }

    public function reportExcel($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('consumable-items.report-excel', [
            'unit' => $unitData
        ]);
    }

    public function printExcel($unit, Request $request, $type)
    {
        if ($request->all_column == '1') {
            $name = '1';
            $category = '1';
            $sub_category = '1';
            $brand = '1';
            $shop = '1';
            $fund = '1';
            $room = '1';
            $unit_data = '1';
            $price = '1';
            $purchase_date = '1';
            $initial_amount = '1';
            $taken_amount = '1';
            $stock_amount = '1';
            $description = '1';
            $insert_date = '1';
        } else {
            $request->name ? $name = '1' : $name = '0';
            $request->category ? $category = '1' : $category = '0';
            $request->sub_category ? $sub_category = '1' : $sub_category = '0';
            $request->brand ? $brand = '1' : $brand = '0';
            $request->shop ? $shop = '1' : $shop = '0';
            $request->fund ? $fund = '1' : $fund = '0';
            $request->room ? $room = '1' : $room = '0';
            $request->unit_data ? $unit_data = '1' : $unit_data = '0';
            $request->price ? $price = '1' : $price = '0';
            $request->purchase_date ? $purchase_date = '1' : $purchase_date = '0';
            $request->initial_amount ? $initial_amount = '1' : $initial_amount = '0';
            $request->taken_amount ? $taken_amount = '1' : $taken_amount = '0';
            $request->stock_amount ? $stock_amount = '1' : $stock_amount = '0';
            $request->description ? $description = '1' : $description = '0';
            $request->insert_date ? $insert_date = '1' : $insert_date = '0';
        }

        $unitData = Unit::where('slug', $unit)->first();
        if ($type == 'separate') {
            $first_date = Carbon::createFromFormat('d/m/Y', $request->firstDate)->format('Y-m-d');
            $last_date = Carbon::createFromFormat('d/m/Y', $request->lastDate)->format('Y-m-d');
        }

        if ($type == 'all') {
            if ($request->all_filter == 'filter-stok-ada') {
                $filter = 'Stok Ada';
                $items = ConsItem::where('unit_id', $unitData->id)->where('stock_amount', '>', 0)->orderBy('purchase_date', 'asc')->orderBy('name', 'asc')->get();
            } elseif ($request->all_filter == 'filter-stok-habis') {
                $filter = 'Stok Habis';
                $items = ConsItem::where('unit_id', $unitData->id)->where('stock_amount', '<=', 0)->orderBy('purchase_date', 'asc')->orderBy('name', 'asc')->get();
            } else {
                $filter = '';
                $items = ConsItem::where('unit_id', $unitData->id)->orderBy('purchase_date', 'asc')->orderBy('name', 'asc')->get();
            }
        } else {
            if ($request->separate_filter == 'filter-stok-ada') {
                $filter = 'Stok Ada';
                $items = ConsItem::where('unit_id', $unitData->id)->where('stock_amount', '>', 0)->whereBetween('purchase_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                    ->orderBy('purchase_date', 'asc')->orderBy('name', 'asc')->get();
            } elseif ($request->separate_filter == 'filter-stok-habis') {
                $filter = 'Stok Habis';
                $items = ConsItem::where('unit_id', $unitData->id)->where('stock_amount', '<=', 0)->whereBetween('purchase_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                    ->orderBy('purchase_date', 'asc')->orderBy('name', 'asc')->get();
            } else {
                $filter = '';
                $items = ConsItem::where('unit_id', $unitData->id)->orderBy('purchase_date', 'asc')->whereBetween('purchase_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                    ->orderBy('name', 'asc')->get();
            }
        }

        if ($items->isEmpty()) {
            return back()->withWarning('Data Yang Diminta Tidak Ditemukan');
        }

        $todayDate = Carbon::now()->format('Ymd');
        if ($type == 'all') {
            $firstDate = Carbon::createFromFormat('Y-m-d', $items->first()->purchase_date)->isoFormat('DD MMMM Y');
            $lastDate = Carbon::createFromFormat('Y-m-d', $items->last()->purchase_date)->isoFormat('DD MMMM Y');
        } else {
            $firstDate = Carbon::createFromFormat('Y-m-d', $first_date)->isoFormat('DD MMMM Y');
            $lastDate = Carbon::createFromFormat('Y-m-d', $last_date)->isoFormat('DD MMMM Y');
        }

        if ($filter != '') {
            if ($firstDate == $lastDate) {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Barang Habis Pakai [' . $filter . '] (' . $firstDate . ').xlsx';
            } else {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Barang Habis Pakai [' . $filter . '] (' . $firstDate . ' - ' . $lastDate . ').xlsx';
            }
        } else {
            if ($firstDate == $lastDate) {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Barang Habis Pakai (' . $firstDate . ').xlsx';
            } else {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Barang Habis Pakai (' . $firstDate . ' - ' . $lastDate . ').xlsx';
            }
        }

        return Excel::download(new ConsItemExport(
            $items,
            $name,
            $category,
            $sub_category,
            $brand,
            $shop,
            $fund,
            $room,
            $unit_data,
            $price,
            $purchase_date,
            $initial_amount,
            $taken_amount,
            $stock_amount,
            $description,
            $insert_date,
        ), $file_name);
    }
}