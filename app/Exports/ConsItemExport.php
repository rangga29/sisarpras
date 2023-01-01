<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ConsItemExport implements FromView, ShouldAutoSize, WithStyles, WithStrictNullComparison
{
    protected $items;
    protected $name;
    protected $category;
    protected $sub_category;
    protected $brand;
    protected $shop;
    protected $fund;
    protected $room;
    protected $unit_data;
    protected $price;
    protected $purchase_date;
    protected $initial_amount;
    protected $taken_amount;
    protected $stock_amount;
    protected $description;
    protected $insert_date;

    function __construct($items, $name, $category, $sub_category, $brand, $shop, $fund, $room, $unit_data, $price, $purchase_date, $initial_amount, $taken_amount, $stock_amount, $description, $insert_date)
    {
        $this->items = $items;
        $this->name = $name;
        $this->category = $category;
        $this->sub_category = $sub_category;
        $this->brand = $brand;
        $this->shop = $shop;
        $this->fund = $fund;
        $this->room = $room;
        $this->unit_data = $unit_data;
        $this->price = $unit_data;
        $this->purchase_date = $purchase_date;
        $this->initial_amount = $initial_amount;
        $this->taken_amount = $taken_amount;
        $this->stock_amount = $stock_amount;
        $this->description = $description;
        $this->insert_date = $insert_date;
    }

    public function view(): View
    {
        return view('consumable-items.print-excel', [
            'items' => $this->items,
            'name' => $this->name,
            'category' => $this->category,
            'sub_category' => $this->sub_category,
            'brand' => $this->brand,
            'shop' => $this->shop,
            'fund' => $this->fund,
            'room' => $this->room,
            'unit' => $this->unit_data,
            'price' => $this->price,
            'purchase_date' => $this->purchase_date,
            'initial_amount' => $this->initial_amount,
            'taken_amount' => $this->taken_amount,
            'stock_amount' => $this->stock_amount,
            'description' => $this->description,
            'insert_date' => $this->insert_date
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }
}