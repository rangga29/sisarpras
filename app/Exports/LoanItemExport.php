<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LoanItemExport implements FromView, ShouldAutoSize, WithStyles, WithStrictNullComparison
{
    protected $items;
    protected $name;
    protected $category;
    protected $sub_category;
    protected $consumer;
    protected $unit_data;
    protected $condition_loan;
    protected $condition_return;
    protected $loan_date;
    protected $return_date;
    protected $description;

    function __construct($items, $name, $category, $sub_category, $consumer, $unit_data, $condition_loan, $condition_return, $loan_date, $return_date, $description)
    {
        $this->items = $items;
        $this->name = $name;
        $this->category = $category;
        $this->sub_category = $sub_category;
        $this->consumer = $consumer;
        $this->unit_data = $unit_data;
        $this->condition_loan = $condition_loan;
        $this->condition_return = $condition_return;
        $this->loan_date = $loan_date;
        $this->return_date = $return_date;
        $this->description = $description;
    }

    public function view(): View
    {
        return view('non-consumable-items.loans.print-excel', [
            'items' => $this->items,
            'name' => $this->name,
            'category' => $this->category,
            'sub_category' => $this->sub_category,
            'consumer' => $this->consumer,
            'unit' => $this->unit_data,
            'condition_loan' => $this->condition_loan,
            'condition_return' => $this->condition_return,
            'loan_date' => $this->loan_date,
            'return_date' => $this->return_date,
            'description' => $this->description
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }
}