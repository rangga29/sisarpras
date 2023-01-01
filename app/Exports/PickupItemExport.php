<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PickupItemExport implements FromView, ShouldAutoSize, WithStyles, WithStrictNullComparison
{
    protected $items;
    protected $name;
    protected $category;
    protected $sub_category;
    protected $consumer;
    protected $unit_data;
    protected $date;
    protected $amount;
    protected $description;

    function __construct($items, $name, $category, $sub_category, $consumer, $unit_data, $date, $amount, $description)
    {
        $this->items = $items;
        $this->name = $name;
        $this->category = $category;
        $this->sub_category = $sub_category;
        $this->consumer = $consumer;
        $this->unit_data = $unit_data;
        $this->date = $date;
        $this->amount = $amount;
        $this->description = $description;
    }

    public function view(): View
    {
        return view('consumable-items.pickup.print-excel', [
            'items' => $this->items,
            'name' => $this->name,
            'category' => $this->category,
            'sub_category' => $this->sub_category,
            'consumer' => $this->consumer,
            'unit' => $this->unit_data,
            'date' => $this->date,
            'amount' => $this->amount,
            'description' => $this->description
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }
}