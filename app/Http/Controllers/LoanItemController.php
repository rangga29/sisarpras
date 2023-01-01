<?php

namespace App\Http\Controllers;

use App\Exports\LoanItemExport;
use App\Models\Unit;
use App\Models\Consumer;
use App\Models\LoanItem;
use App\Models\NonConsItem;
use Illuminate\Support\Str;
use App\Http\Requests\LoanItemEditRequest;
use App\Http\Requests\LoanItemCreateRequest;
use App\Http\Requests\ReturnLoanItemCreateRequest;
use App\Http\Requests\ReturnLoanItemEditRequest;
use App\Models\NonConsCondition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;

class LoanItemController extends Controller
{
    public function index($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.loans.index', [
            'loans' => LoanItem::whereNull('return_date')->orderBy('loan_date', 'desc')->get(),
            'return_loans' => LoanItem::whereNotNull('return_date')->orderBy('loan_date', 'desc')->get(),
            'unit' => $unitData
        ]);
    }

    public function create($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.loans.create', [
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
            'consumers' => Consumer::where('unit_id', $unitData->id)->orderBy('name', 'asc')->get(),
            'unit' => $unitData
        ]);
    }

    public function store($unit, LoanItemCreateRequest $request)
    {
        $validateData = $request->validated();

        $non_cons_item = NonConsItem::firstWhere('id', $validateData['non_cons_item_id']);
        $non_cons_item->availability = 0;
        $non_cons_item->save();

        $validateData['unit_id'] = Unit::where('slug', $unit)->first()->id;
        $validateData['loan_code'] = Str::random(10);
        $validateData['con_loan_id'] = $non_cons_item->non_cons_condition_id;
        $validateData['loan_date'] = Carbon::createFromFormat('d/m/Y', $validateData['loan_date'])->format('Y-m-d');

        LoanItem::create($validateData);
        return redirect()->route('non-consumable-items.loan-items', $unit)->withSuccess('Data Peminjaman Berhasil Ditambahkan');
    }

    public function edit($unit, LoanItem $loan)
    {
        $unitData = Unit::where('slug', $unit)->first();
        $loan_date = Carbon::createFromFormat('Y-m-d', $loan->loan_date)->format('d/m/Y');
        return view('non-consumable-items.loans.edit', [
            'loan' => $loan,
            'loan_date' => $loan_date,
            'items' => NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                ->where('non_cons_items.availability', 1)
                ->where('non_cons_items.non_cons_condition_id', 1)
                ->where('non_cons_items.unit_id', $unitData->id)
                ->orWhere('non_cons_items.id', $loan->non_cons_item->id)
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
            'consumers' => Consumer::where('unit_id', $unitData->id)->orderBy('name', 'asc')->get(),
            'unit' => $unitData
        ]);
    }

    public function update($unit, LoanItemEditRequest $request, LoanItem $loan)
    {
        $validateData = $request->validated();

        if ($validateData['non_cons_item_id'] != $loan->non_cons_item_id) {
            $old_data = NonConsItem::firstWhere('id', $loan->non_cons_item_id);
            $old_data->availability = 1;
            $old_data->save();

            $new_data = NonConsItem::firstWhere('id', $validateData['non_cons_item_id']);
            $new_data->availability = 0;
            $new_data->save();

            $validateData['con_loan_id'] = $new_data->non_cons_condition_id;
        }

        $validateData['loan_date'] = Carbon::createFromFormat('d/m/Y', $validateData['loan_date'])->format('Y-m-d');

        $loan->update($validateData);
        return redirect()->route('non-consumable-items.loan-items', $unit)->withSuccess('Data Peminjaman Berhasil Diubah');
    }

    public function delete($unit, LoanItem $loan)
    {
        $non_cons_item = NonConsItem::firstWhere('id', $loan->non_cons_item_id);
        if ($loan->return_date == null) {
            $non_cons_item->availability = 1;
            $non_cons_item->save();
        }

        $loan->delete();
        return redirect()->route('non-consumable-items.loan-items', $unit)->withSuccess('Data Peminjaman Berhasil Dihapus');
    }

    public function createReturnItem($unit, LoanItem $loan)
    {
        $unitData = Unit::where('slug', $unit)->first();
        $loan_date = Carbon::createFromFormat('Y-m-d', $loan->loan_date)->format('d/m/Y');
        return view('non-consumable-items.loans.create-return', [
            'loan' => $loan,
            'loan_date' => $loan_date,
            'items' => NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                ->where('non_cons_items.availability', 1)
                ->where('non_cons_items.non_cons_condition_id', 1)
                ->where('non_cons_items.unit_id', $unitData->id)
                ->orWhere('non_cons_items.id', $loan->non_cons_item->id)
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
            'consumers' => Consumer::where('unit_id', $unitData->id)->orderBy('name', 'asc')->get(),
            'conditions' => NonConsCondition::orderBy('id', 'asc')->get(),
            'unit' => $unitData
        ]);
    }

    public function storeReturnItem($unit, LoanItem $loan, ReturnLoanItemCreateRequest $request)
    {
        $validateData = $request->validated();

        $non_cons_item = NonConsItem::firstWhere('id', $loan->non_cons_item_id);
        $non_cons_item->non_cons_condition_id = $validateData['con_return_id'];
        $non_cons_item->availability = 1;
        $non_cons_item->save();

        $validateData['return_date'] = Carbon::createFromFormat('d/m/Y', $validateData['return_date'])->format('Y-m-d');

        $loan->update($validateData);
        return redirect()->route('non-consumable-items.loan-items', $unit)->withSuccess('Data Pengembalian Berhasil Ditambah');
    }

    public function editReturnItem($unit, LoanItem $loan)
    {
        $unitData = Unit::where('slug', $unit)->first();
        $loan_date = Carbon::createFromFormat('Y-m-d', $loan->loan_date)->format('d/m/Y');
        $return_date = Carbon::createFromFormat('Y-m-d', $loan->return_date)->format('d/m/Y');
        return view('non-consumable-items.loans.edit-return', [
            'loan' => $loan,
            'loan_date' => $loan_date,
            'return_date' => $return_date,
            'items' => NonConsItem::join('non_cons_sub_categories', 'non_cons_sub_categories.id', '=', 'non_cons_items.non_cons_sub_category_id')
                ->join('non_cons_categories', 'non_cons_categories.id', '=', 'non_cons_sub_categories.non_cons_category_id')
                ->where('non_cons_items.availability', 1)
                ->where('non_cons_items.non_cons_condition_id', 1)
                ->where('non_cons_items.unit_id', $unitData->id)
                ->orWhere('non_cons_items.id', $loan->non_cons_item->id)
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
            'consumers' => Consumer::where('unit_id', $unitData->id)->orderBy('name', 'asc')->get(),
            'conditions' => NonConsCondition::orderBy('id', 'asc')->get(),
            'unit' => $unitData
        ]);
    }

    public function updateReturnItem($unit, LoanItem $loan, ReturnLoanItemEditRequest $request)
    {
        $validateData = $request->validated();

        $non_cons_item = NonConsItem::firstWhere('id', $loan->non_cons_item_id);
        if ($loan->con_return_id != $validateData['con_return_id']) {
            if ($non_cons_item->availability == 0) {
                return back()->withWarning('Barang Sedang Dipinjam atau Ditempatkan');
            }
        }
        $non_cons_item->non_cons_condition_id = $validateData['con_return_id'];
        $non_cons_item->save();

        $validateData['return_date'] = Carbon::createFromFormat('d/m/Y', $validateData['return_date'])->format('Y-m-d');

        $loan->update($validateData);
        return redirect()->route('non-consumable-items.loan-items', $unit)->withSuccess('Data Pengembalian Berhasil Diubah');
    }

    public function reportPdf($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.loans.report-pdf', [
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
        $request->consumer ? $consumer = '1' : $consumer = '0';
        $request->unit_data ? $unit_data = '1' : $unit_data = '0';
        $request->condition_loan ? $condition_loan = '1' : $condition_loan = '0';
        $request->condition_return ? $condition_return = '1' : $condition_return = '0';
        $request->loan_date ? $loan_date = '1' : $loan_date = '0';
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->all_item_name)
                        ->where('loan_items.return_date', '=', null)
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('loan_items.return_date', '=', null)
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->all_item_name)
                        ->where('loan_items.return_date', '!=', null)
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('loan_items.return_date', '!=', null)
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->all_item_name)
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->separate_item_name)
                        ->where('loan_items.return_date', '=', null)
                        ->whereBetween('loan_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('loan_items.return_date', '=', null)
                        ->whereBetween('loan_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->separate_item_name)
                        ->where('loan_items.return_date', '!=', null)
                        ->whereBetween('loan_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('loan_items.return_date', '!=', null)
                        ->whereBetween('loan_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->separate_item_name)
                        ->whereBetween('loan_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->whereBetween('loan_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
            $firstDate = Carbon::createFromFormat('Y-m-d', $items->first()->loan_date)->isoFormat('DD MMMM Y');
            $lastDate = Carbon::createFromFormat('Y-m-d', $items->last()->loan_date)->isoFormat('DD MMMM Y');
        } else {
            $firstDate = Carbon::createFromFormat('Y-m-d', $first_date)->isoFormat('DD MMMM Y');
            $lastDate = Carbon::createFromFormat('Y-m-d', $last_date)->isoFormat('DD MMMM Y');
        }

        if ($itemName != '') {
            if ($filter != '') {
                if ($firstDate == $lastDate) {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Peminjaman Barang [' . $itemName . ' - ' . $filter . '] (' . $firstDate . ').pdf';
                } else {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Peminjaman Barang [' . $itemName . ' - ' . $filter . '] (' . $firstDate . ' - ' . $lastDate . ').pdf';
                }
            } else {
                if ($firstDate == $lastDate) {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Peminjaman Barang [' . $itemName . '] (' . $firstDate . ').pdf';
                } else {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Peminjaman Barang [' . $itemName . '] (' . $firstDate . ' - ' . $lastDate . ').pdf';
                }
            }
        } else {
            if ($filter != '') {
                if ($firstDate == $lastDate) {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Peminjaman Barang [' . $filter . '] (' . $firstDate . ').pdf';
                } else {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Peminjaman Barang [' . $filter . '] (' . $firstDate . ' - ' . $lastDate . ').pdf';
                }
            } else {
                if ($firstDate == $lastDate) {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Peminjaman Barang (' . $firstDate . ').pdf';
                } else {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Peminjaman Barang (' . $firstDate . ' - ' . $lastDate . ').pdf';
                }
            }
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadview('non-consumable-items.loans.print-pdf', [
            'unitData' => $unitData,
            'title' => 'Laporan Data Peminjaman Barang',
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
            'consumer' => $consumer,
            'unit' => $unit_data,
            'condition_loan' => $condition_loan,
            'condition_return' => $condition_return,
            'loan_date' => $loan_date,
            'return_date' => $return_date,
            'description' => $description
        ])->setPaper('a4', 'landscape');
        return $pdf->stream($file_name);
    }

    public function reportExcel($unit)
    {
        $unitData = Unit::where('slug', $unit)->first();
        return view('non-consumable-items.loans.report-excel', [
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
            $consumer = '1';
            $unit_data = '1';
            $condition_loan = '1';
            $condition_return = '1';
            $loan_date = '1';
            $return_date = '1';
            $description = '1';
        } else {
            $request->name ? $name = '1' : $name = '0';
            $request->category ? $category = '1' : $category = '0';
            $request->sub_category ? $sub_category = '1' : $sub_category = '0';
            $request->consumer ? $consumer = '1' : $consumer = '0';
            $request->unit_data ? $unit_data = '1' : $unit_data = '0';
            $request->condition_loan ? $condition_loan = '1' : $condition_loan = '0';
            $request->condition_return ? $condition_return = '1' : $condition_return = '0';
            $request->loan_date ? $loan_date = '1' : $loan_date = '0';
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->all_item_name)
                        ->where('loan_items.return_date', '=', null)
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('loan_items.return_date', '=', null)
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->all_item_name)
                        ->where('loan_items.return_date', '!=', null)
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('loan_items.return_date', '!=', null)
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->all_item_name)
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->separate_item_name)
                        ->where('loan_items.return_date', '=', null)
                        ->whereBetween('loan_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('loan_items.return_date', '=', null)
                        ->whereBetween('loan_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->separate_item_name)
                        ->where('loan_items.return_date', '!=', null)
                        ->whereBetween('loan_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('loan_items.return_date', '!=', null)
                        ->whereBetween('loan_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->where('non_cons_items.id', $request->separate_item_name)
                        ->whereBetween('loan_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
                    $items = LoanItem::join('non_cons_items', 'loan_items.non_cons_item_id', '=', 'non_cons_items.id')
                        ->join('non_cons_sub_categories', 'non_cons_items.non_cons_sub_category_id', '=', 'non_cons_sub_categories.id')
                        ->join('non_cons_categories', 'non_cons_sub_categories.non_cons_category_id', '=', 'non_cons_categories.id')
                        ->where('loan_items.unit_id', $unitData->id)
                        ->whereBetween('loan_date', [$first_date . ' 00:00:00', $last_date . ' 23:59:59'])
                        ->orderBy('loan_items.loan_date', 'asc')
                        ->orderBy('non_cons_categories.category_code', 'asc')
                        ->orderBy('non_cons_sub_categories.sub_category_code', 'asc')
                        ->orderBy('non_cons_items.item_number', 'asc')
                        ->orderBy('non_cons_items.name', 'asc')
                        ->get([
                            'loan_items.*',
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
            $firstDate = Carbon::createFromFormat('Y-m-d', $items->first()->loan_date)->isoFormat('DD MMMM Y');
            $lastDate = Carbon::createFromFormat('Y-m-d', $items->last()->loan_date)->isoFormat('DD MMMM Y');
        } else {
            $firstDate = Carbon::createFromFormat('Y-m-d', $first_date)->isoFormat('DD MMMM Y');
            $lastDate = Carbon::createFromFormat('Y-m-d', $last_date)->isoFormat('DD MMMM Y');
        }

        if ($itemName != '') {
            if ($filter != '') {
                if ($firstDate == $lastDate) {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Peminjaman Barang [' . $itemName . ' - ' . $filter . '] (' . $firstDate . ').xlsx';
                } else {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Peminjaman Barang [' . $itemName . ' - ' . $filter . '] (' . $firstDate . ' - ' . $lastDate . ').xlsx';
                }
            } else {
                if ($firstDate == $lastDate) {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Peminjaman Barang [' . $itemName . '] (' . $firstDate . ').xlsx';
                } else {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Peminjaman Barang [' . $itemName . '] (' . $firstDate . ' - ' . $lastDate . ').xlsx';
                }
            }
        } else {
            if ($filter != '') {
                if ($firstDate == $lastDate) {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Peminjaman Barang [' . $filter . '] (' . $firstDate . ').xlsx';
                } else {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Peminjaman Barang [' . $filter . '] (' . $firstDate . ' - ' . $lastDate . ').xlsx';
                }
            } else {
                if ($firstDate == $lastDate) {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Peminjaman Barang (' . $firstDate . ').xlsx';
                } else {
                    $file_name = '[' . $todayDate . '] SARPRAS - Laporan Data Peminjaman Barang (' . $firstDate . ' - ' . $lastDate . ').xlsx';
                }
            }
        }

        return Excel::download(new LoanItemExport(
            $items,
            $name,
            $category,
            $sub_category,
            $consumer,
            $unit_data,
            $condition_loan,
            $condition_return,
            $loan_date,
            $return_date,
            $description
        ), $file_name);
    }
}