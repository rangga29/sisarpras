<?php

namespace App\Http\Controllers;

use App\Exports\NonConsItemExport;
use Carbon\Carbon;
use App\Models\Fund;
use App\Models\Room;
use App\Models\Shop;
use App\Models\Unit;
use App\Models\Brand;
use App\Models\NonConsItem;
use Illuminate\Support\Str;
use App\Models\NonConsCondition;
use App\Models\NonConsSubCategory;
use Intervention\Image\Facades\Image;
use App\Http\Requests\NonConsItemEditRequest;
use App\Http\Requests\NonConsItemCreateRequest;
use App\Models\LoanItem;
use App\Models\PlacementItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class NonConsItemController extends Controller
{
    public function index($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.index', [
            'items' => NonConsItem::where('unit_id', $unitData->id)->orderBy('purchase_date', 'desc')->get(),
            'items_available' => NonConsItem::where('availability', true)->where('non_cons_condition_id', 1)->where('unit_id', $unitData->id)->orderBy('purchase_date', 'desc')->get(),
            'items_not_available' => NonConsItem::where('availability', false)->where('non_cons_condition_id', 1)->where('unit_id', $unitData->id)->orderBy('purchase_date', 'desc')->get(),
            'items_broken' => NonConsItem::where('non_cons_condition_id', 2)->where('unit_id', $unitData->id)->orderBy('purchase_date', 'desc')->get(),
            'items_grant' => NonConsItem::where('non_cons_condition_id', 3)->where('unit_id', $unitData->id)->orderBy('purchase_date', 'desc')->get(),
            'items_delete' => NonConsItem::where('non_cons_condition_id', 4)->where('unit_id', $unitData->id)->orderBy('purchase_date', 'desc')->get(),
            'unit' => $unitData
        ]);
    }

    public function create($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.create', [
            'sub_categories' => NonConsSubCategory::join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                ->orderBy('non_cons_categories.category_code', 'asc')
                ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                ->get([
                    'non_cons_categories.id as c_id',
                    'non_cons_categories.category_code as c_code',
                    'non_cons_categories.category_name as c_name',
                    'non_cons_sub_categories.id as sc_id',
                    'non_cons_sub_categories.sub_category_code as sc_code',
                    'non_cons_sub_categories.sub_category_name as sc_name'
                ]),
            'brands' => Brand::orderBy('name', 'asc')->get(),
            'shops' => Shop::orderBy('name', 'asc')->get(),
            'funds' => Fund::orderBy('name', 'asc')->get(),
            'rooms' => Room::where('unit_id', $unitData->id)->orderBy('name', 'asc')->get(),
            'conditions' => NonConsCondition::orderBy('id', 'asc')->get(),
            'unit' => $unitData
        ]);
    }

    public function store($unit, NonConsItemCreateRequest $request)
    {
        $validateData = $request->validated();

        $items = NonConsItem::where('non_cons_sub_category_id', $validateData['non_cons_sub_category_id'])->get();

        foreach ($items as $item) {
            if ($item->item_number == $validateData['item_number']) {
                return back()->withWarning('Nomor Barang Sudah Digunakan Pada Sub Kategori Tersebut')->withInput();
            }
        }

        $validateData['item_code'] = Str::random(10);
        $validateData['purchase_date'] = Carbon::createFromFormat('d/m/Y', $validateData['purchase_date'])->format('Y-m-d');

        $filenameImage = uniqid() . '.png';
        $imgData = Image::make($validateData['image'])->resize(320, 240)->encode('png');
        Storage::put('public/non-consumable-items/' . $filenameImage, $imgData);
        $validateData['image'] = $filenameImage;

        $filenameReceipt = uniqid() . '.pdf';
        Storage::putFileAs('public/non-consumable-items/', $validateData['receipt'], $filenameReceipt);
        $validateData['receipt'] = $filenameReceipt;

        NonConsItem::create($validateData);
        return redirect()->route('non-consumable-items', $unit)->withSuccess('Data Barang Berhasil Ditambahkan');
    }

    public function show($unit, NonConsItem $item)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.show', [
            'item' => $item,
            'unit' => $unitData
        ]);
    }

    public function edit($unit, NonConsItem $item)
    {
        $unitData = Unit::where('slug', $unit)->first();
        $purchase_date = Carbon::createFromFormat('Y-m-d', $item->purchase_date)->format('d/m/Y');
        return view('non-consumable-items.edit', [
            'item' => $item,
            'purchase_date' => $purchase_date,
            'sub_categories' => NonConsSubCategory::join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                ->orderBy('non_cons_categories.category_code', 'asc')
                ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                ->get([
                    'non_cons_categories.id as c_id',
                    'non_cons_categories.category_code as c_code',
                    'non_cons_categories.category_name as c_name',
                    'non_cons_sub_categories.id as sc_id',
                    'non_cons_sub_categories.sub_category_code as sc_code',
                    'non_cons_sub_categories.sub_category_name as sc_name'
                ]),
            'brands' => Brand::orderBy('name', 'asc')->get(),
            'shops' => Shop::orderBy('name', 'asc')->get(),
            'funds' => Fund::orderBy('name', 'asc')->get(),
            'rooms' => Room::where('unit_id', $unitData->id)->orderBy('name', 'asc')->get(),
            'conditions' => NonConsCondition::orderBy('id', 'asc')->get(),
            'unit' => $unitData
        ]);
    }

    public function update($unit, NonConsItemEditRequest $request, NonConsItem $item)
    {
        $validateData = $request->validated();

        if ($validateData['non_cons_sub_category_id'] == $item->non_cons_sub_category_id) {
            if ($validateData['item_number'] != $item->item_number) {
                $non_cons_items = NonConsItem::where('non_cons_sub_category_id', $validateData['non_cons_sub_category_id'])->get();
                foreach ($non_cons_items as $non_cons_item) {
                    if ($non_cons_item->item_number == $validateData['item_number']) {
                        return back()->withWarning('Nomor Barang Sudah Digunakan Pada Sub Kategori Yang Dipilih')->withInput();
                    }
                }
            }
        } else {
            $non_cons_items = NonConsItem::where('non_cons_sub_category_id', $validateData['non_cons_sub_category_id'])->get();
            foreach ($non_cons_items as $non_cons_item) {
                if ($non_cons_item->item_number == $validateData['item_number']) {
                    return back()->withWarning('Nomor Barang Sudah Digunakan Pada Sub Kategori Yang Dipilih')->withInput();
                }
            }
        }

        if ($request->image) {
            $filenameImage = uniqid() . '.png';
            $imgData = Image::make($validateData['image'])->resize(320, 240)->encode('png');
            Storage::put('public/non-consumable-items/' . $filenameImage, $imgData);
            Storage::delete('public/non-consumable-items/' . $item->image);
            $validateData['image'] = $filenameImage;
        }

        if ($request->receipt) {
            $filenameReceipt = uniqid() . '.pdf';
            Storage::putFileAs('public/non-consumable-items/', $validateData['receipt'], $filenameReceipt);
            Storage::delete('public/non-consumable-items/' . $item->receipt);
            $validateData['receipt'] = $filenameReceipt;
        }

        $validateData['purchase_date'] = Carbon::createFromFormat('d/m/Y', $validateData['purchase_date'])->format('Y-m-d');

        $item->update($validateData);
        return redirect()->route('non-consumable-items', $unit)->withSuccess('Data Barang Berhasil Diubah');
    }

    public function delete($unit, NonConsItem $item)
    {
        $loan = LoanItem::where('cons_item_id', $item->id)->get();
        if ($loan->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Peminjaman');
        }

        $placement = PlacementItem::where('cons_item_id', $item->id)->get();
        if ($placement->isNotEmpty()) {
            return back()->withWarning('Data Masih Digunakan Di Data Penempatan');
        }

        Storage::delete('public/non-consumable-items/' . $item->image);
        Storage::delete('public/non-consumable-items/' . $item->receipt);
        $item->delete();
        return redirect()->route('non-consumable-items', $unit)->withSuccess('Data Barang Berhasil Dihapus');
    }

    public function reportPdf($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.report-pdf', [
            'conditions' => NonConsCondition::orderBy('id', 'asc')->get(),
            'unit' => $unitData
        ]);
    }

    public function printPdf($unit, Request $request, $type)
    {
        $request->code ? $code = '1' : $code = '0';
        $request->name ? $name = '1' : $name = '0';
        $request->category ? $category = '1' : $category = '0';
        $request->sub_category ? $sub_category = '1' : $sub_category = '0';
        $request->brand ? $brand = '1' : $brand = '0';
        $request->shop ? $shop = '1' : $shop = '0';
        $request->fund ? $fund = '1' : $fund = '0';
        $request->room ? $room = '1' : $room = '0';
        $request->condition ? $condition = '1' : $condition = '0';
        $request->unit_data ? $unit_data = '1' : $unit_data = '0';
        $request->price ? $price = '1' : $price = '0';
        $request->purchase_date ? $purchase_date = '1' : $purchase_date = '0';
        $request->availability ? $availability = '1' : $availability = '0';
        $request->include ? $include = '1' : $include = '0';
        $request->description ? $description = '1' : $description = '0';
        $request->insert_date ? $insert_date = '1' : $insert_date = '0';

        $unitData = Unit::where('slug', $unit)->first();
        if ($type == 'separate') {
            $first_date = Carbon::createFromFormat('d/m/Y', $request->firstDate)->format('Y-m-d');
            $last_date = Carbon::createFromFormat('d/m/Y', $request->lastDate)->format('Y-m-d');
        }

        if ($type == 'all') {
            if ($request->all_condition_name != '') {
                $filter = NonConsCondition::where('id', $request->all_condition_name)->first()->name;
                $items = NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                    ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                    ->where('non_cons_items.non_cons_condition_id', $request->all_condition_name)
                    ->where('non_cons_items.unit_id', $unitData->id)
                    ->orderBy('non_cons_items.purchase_date', 'asc')
                    ->orderBy('non_cons_categories.category_code', 'asc')
                    ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                    ->orderBy('non_cons_items.item_number', 'asc')
                    ->get([
                        'non_cons_items.*',
                        'non_cons_categories.id as c_id',
                        'non_cons_categories.category_code as c_code',
                        'non_cons_categories.category_name as c_name',
                        'non_cons_sub_categories.id as sc_id',
                        'non_cons_sub_categories.sub_category_code as sc_code',
                        'non_cons_sub_categories.sub_category_name as sc_name',
                    ]);
            } else {
                $filter = '';
                $items = NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                    ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                    ->where('non_cons_items.unit_id', $unitData->id)
                    ->orderBy('non_cons_items.purchase_date', 'asc')
                    ->orderBy('non_cons_categories.category_code', 'asc')
                    ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                    ->orderBy('non_cons_items.item_number', 'asc')
                    ->get([
                        'non_cons_items.*',
                        'non_cons_categories.id as c_id',
                        'non_cons_categories.category_code as c_code',
                        'non_cons_categories.category_name as c_name',
                        'non_cons_sub_categories.id as sc_id',
                        'non_cons_sub_categories.sub_category_code as sc_code',
                        'non_cons_sub_categories.sub_category_name as sc_name',
                    ]);
            }
        } else {
            if ($request->separate_condition_name != '') {
                $filter = NonConsCondition::where('id', $request->separate_condition_name)->first()->name;
                $items = NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                    ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                    ->where('non_cons_items.non_cons_condition_id', $request->separate_condition_name)
                    ->where('non_cons_items.unit_id', $unitData->id)
                    ->whereBetween('non_cons_items.purchase_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                    ->orderBy('non_cons_items.purchase_date', 'asc')
                    ->orderBy('non_cons_categories.category_code', 'asc')
                    ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                    ->orderBy('non_cons_items.item_number', 'asc')
                    ->get([
                        'non_cons_items.*',
                        'non_cons_categories.id as c_id',
                        'non_cons_categories.category_code as c_code',
                        'non_cons_categories.category_name as c_name',
                        'non_cons_sub_categories.id as sc_id',
                        'non_cons_sub_categories.sub_category_code as sc_code',
                        'non_cons_sub_categories.sub_category_name as sc_name',
                    ]);
            } else {
                $filter = '';
                $items = NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                    ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                    ->where('non_cons_items.unit_id', $unitData->id)
                    ->whereBetween('non_cons_items.purchase_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                    ->orderBy('non_cons_items.purchase_date', 'asc')
                    ->orderBy('non_cons_categories.category_code', 'asc')
                    ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                    ->orderBy('non_cons_items.item_number', 'asc')
                    ->get([
                        'non_cons_items.*',
                        'non_cons_categories.id as c_id',
                        'non_cons_categories.category_code as c_code',
                        'non_cons_categories.category_name as c_name',
                        'non_cons_sub_categories.id as sc_id',
                        'non_cons_sub_categories.sub_category_code as sc_code',
                        'non_cons_sub_categories.sub_category_name as sc_name',
                    ]);
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
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Barang Tidak Habis Pakai [' . $filter . '] (' . $firstDate . ').pdf';
            } else {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Barang Tidak Habis Pakai [' . $filter . '] (' . $firstDate . ' - ' . $lastDate . ').pdf';
            }
        } else {
            if ($firstDate == $lastDate) {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Barang Tidak Habis Pakai (' . $firstDate . ').pdf';
            } else {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Barang Tidak Habis Pakai (' . $firstDate . ' - ' . $lastDate . ').pdf';
            }
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadview('non-consumable-items.print-pdf', [
            'unitData' => $unitData,
            'title' => 'Laporan Data Barang Tidak Habis Pakai',
            'subTitle1' => '',
            'subTitle2' => '',
            'filter' => ($filter != '' ? 'Filter : ' . $filter : ''),
            'items' => $items,
            'firstDate' => $firstDate,
            'lastDate' => $lastDate,
            'todayDate' => $todayDateConvert,
            'code' => $code,
            'name' => $name,
            'category' => $category,
            'sub_category' => $sub_category,
            'brand' => $brand,
            'shop' => $shop,
            'fund' => $fund,
            'room' => $room,
            'condition' => $condition,
            'unit' => $unit_data,
            'price' => $price,
            'purchase_date' => $purchase_date,
            'availability' => $availability,
            'include' => $include,
            'description' => $description,
            'insert_date' => $insert_date
        ])->setPaper('a4', 'landscape');
        return $pdf->stream($file_name);
    }

    public function reportExcel($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.report-excel', [
            'conditions' => NonConsCondition::orderBy('id', 'asc')->get(),
            'unit' => $unitData
        ]);
    }

    public function printExcel($unit, Request $request, $type)
    {
        if ($request->all_column == '1') {
            $code = '1';
            $name = '1';
            $category = '1';
            $sub_category = '1';
            $brand = '1';
            $shop = '1';
            $fund = '1';
            $room = '1';
            $condition = '1';
            $unit_data = '1';
            $price = '1';
            $purchase_date = '1';
            $availability = '1';
            $include = '1';
            $description = '1';
            $insert_date = '1';
        } else {
            $request->code ? $code = '1' : $code = '0';
            $request->name ? $name = '1' : $name = '0';
            $request->category ? $category = '1' : $category = '0';
            $request->sub_category ? $sub_category = '1' : $sub_category = '0';
            $request->brand ? $brand = '1' : $brand = '0';
            $request->shop ? $shop = '1' : $shop = '0';
            $request->fund ? $fund = '1' : $fund = '0';
            $request->room ? $room = '1' : $room = '0';
            $request->condition ? $condition = '1' : $condition = '0';
            $request->unit_data ? $unit_data = '1' : $unit_data = '0';
            $request->price ? $price = '1' : $price = '0';
            $request->purchase_date ? $purchase_date = '1' : $purchase_date = '0';
            $request->availability ? $availability = '1' : $availability = '0';
            $request->include ? $include = '1' : $include = '0';
            $request->description ? $description = '1' : $description = '0';
            $request->insert_date ? $insert_date = '1' : $insert_date = '0';
        }

        $unitData = Unit::where('slug', $unit)->first();
        if ($type == 'separate') {
            $first_date = Carbon::createFromFormat('d/m/Y', $request->firstDate)->format('Y-m-d');
            $last_date = Carbon::createFromFormat('d/m/Y', $request->lastDate)->format('Y-m-d');
        }

        if ($type == 'all') {
            if ($request->all_condition_name != '') {
                $filter = NonConsCondition::where('id', $request->all_condition_name)->first()->name;
                $items = NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                    ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                    ->where('non_cons_items.non_cons_condition_id', $request->all_condition_name)
                    ->where('non_cons_items.unit_id', $unitData->id)
                    ->orderBy('non_cons_items.purchase_date', 'asc')
                    ->orderBy('non_cons_categories.category_code', 'asc')
                    ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                    ->orderBy('non_cons_items.item_number', 'asc')
                    ->get([
                        'non_cons_items.*',
                        'non_cons_categories.id as c_id',
                        'non_cons_categories.category_code as c_code',
                        'non_cons_categories.category_name as c_name',
                        'non_cons_sub_categories.id as sc_id',
                        'non_cons_sub_categories.sub_category_code as sc_code',
                        'non_cons_sub_categories.sub_category_name as sc_name',
                    ]);
            } else {
                $filter = '';
                $items = NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                    ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                    ->where('non_cons_items.unit_id', $unitData->id)
                    ->orderBy('non_cons_items.purchase_date', 'asc')
                    ->orderBy('non_cons_categories.category_code', 'asc')
                    ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                    ->orderBy('non_cons_items.item_number', 'asc')
                    ->get([
                        'non_cons_items.*',
                        'non_cons_categories.id as c_id',
                        'non_cons_categories.category_code as c_code',
                        'non_cons_categories.category_name as c_name',
                        'non_cons_sub_categories.id as sc_id',
                        'non_cons_sub_categories.sub_category_code as sc_code',
                        'non_cons_sub_categories.sub_category_name as sc_name',
                    ]);
            }
        } else {
            if ($request->separate_condition_name != '') {
                $filter = NonConsCondition::where('id', $request->separate_condition_name)->first()->name;
                $items = NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                    ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                    ->where('non_cons_items.non_cons_condition_id', $request->separate_condition_name)
                    ->where('non_cons_items.unit_id', $unitData->id)
                    ->whereBetween('non_cons_items.purchase_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                    ->orderBy('non_cons_items.purchase_date', 'asc')
                    ->orderBy('non_cons_categories.category_code', 'asc')
                    ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                    ->orderBy('non_cons_items.item_number', 'asc')
                    ->get([
                        'non_cons_items.*',
                        'non_cons_categories.id as c_id',
                        'non_cons_categories.category_code as c_code',
                        'non_cons_categories.category_name as c_name',
                        'non_cons_sub_categories.id as sc_id',
                        'non_cons_sub_categories.sub_category_code as sc_code',
                        'non_cons_sub_categories.sub_category_name as sc_name',
                    ]);
            } else {
                $filter = '';
                $items = NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                    ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                    ->where('non_cons_items.unit_id', $unitData->id)
                    ->whereBetween('non_cons_items.purchase_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                    ->orderBy('non_cons_items.purchase_date', 'asc')
                    ->orderBy('non_cons_categories.category_code', 'asc')
                    ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                    ->orderBy('non_cons_items.item_number', 'asc')
                    ->get([
                        'non_cons_items.*',
                        'non_cons_categories.id as c_id',
                        'non_cons_categories.category_code as c_code',
                        'non_cons_categories.category_name as c_name',
                        'non_cons_sub_categories.id as sc_id',
                        'non_cons_sub_categories.sub_category_code as sc_code',
                        'non_cons_sub_categories.sub_category_name as sc_name',
                    ]);
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
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Barang Tidak Habis Pakai [' . $filter . '] (' . $firstDate . ').xlsx';
            } else {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Barang Tidak Habis Pakai [' . $filter . '] (' . $firstDate . ' - ' . $lastDate . ').xlsx';
            }
        } else {
            if ($firstDate == $lastDate) {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Barang Tidak Habis Pakai (' . $firstDate . ').xlsx';
            } else {
                $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Barang Tidak Habis Pakai (' . $firstDate . ' - ' . $lastDate . ').xlsx';
            }
        }

        return Excel::download(new NonConsItemExport(
            $items,
            $code,
            $name,
            $category,
            $sub_category,
            $brand,
            $shop,
            $fund,
            $room,
            $condition,
            $unit_data,
            $price,
            $purchase_date,
            $availability,
            $include,
            $description,
            $insert_date
        ), $file_name);
    }
}