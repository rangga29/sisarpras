<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NonConsItemExport implements FromView, ShouldAutoSize, WithStyles, WithStrictNullComparison
{
    protected $items;
    protected $code;
    protected $name;
    protected $category;
    protected $sub_category;
    protected $brand;
    protected $shop;
    protected $fund;
    protected $room;
    protected $condition;
    protected $unit_data;
    protected $price;
    protected $purchase_date;
    protected $availability;
    protected $include;
    protected $description;
    protected $insert_date;

    function __construct($items, $code, $name, $category, $sub_category, $brand, $shop, $fund, $room, $condition, $unit_data, $price, $purchase_date, $availability, $include, $description, $insert_date)
    {
        $this->items = $items;
        $this->code = $code;
        $this->name = $name;
        $this->category = $category;
        $this->sub_category = $sub_category;
        $this->brand = $brand;
        $this->shop = $shop;
        $this->fund = $fund;
        $this->room = $room;
        $this->condition = $condition;
        $this->unit_data = $unit_data;
        $this->price = $price;
        $this->purchase_date = $purchase_date;
        $this->availability = $availability;
        $this->include = $include;
        $this->description = $description;
        $this->insert_date = $insert_date;
    }

    public function view(): View
    {
        return view('non-consumable-items.print-excel', [
            'items' => $this->items,
            'code' => $this->code,
            'name' => $this->name,
            'category' => $this->category,
            'sub_category' => $this->sub_category,
            'brand' => $this->brand,
            'shop' => $this->shop,
            'fund' => $this->fund,
            'room' => $this->room,
            'condition' => $this->condition,
            'unit' => $this->unit_data,
            'price' => $this->price,
            'purchase_date' => $this->purchase_date,
            'availability' => $this->availability,
            'include' => $this->include,
            'description' => $this->description,
            'insert_date' => $this->insert_date
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }
}