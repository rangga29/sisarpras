<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PlacementItemExport implements FromView, ShouldAutoSize, WithStyles, WithStrictNullComparison
{
    protected $items;
    protected $name;
    protected $category;
    protected $sub_category;
    protected $room;
    protected $unit_data;
    protected $condition_placement;
    protected $condition_return;
    protected $placement_date;
    protected $return_date;
    protected $description;

    function __construct($items, $name, $category, $sub_category, $room, $unit_data, $condition_placement, $condition_return, $placement_date, $return_date, $description)
    {
        $this->items = $items;
        $this->name = $name;
        $this->category = $category;
        $this->sub_category = $sub_category;
        $this->room = $room;
        $this->unit_data = $unit_data;
        $this->condition_placement = $condition_placement;
        $this->condition_return = $condition_return;
        $this->placement_date = $placement_date;
        $this->return_date = $return_date;
        $this->description = $description;
    }

    public function view(): View
    {
        return view('non-consumable-items.placements.print-excel', [
            'items' => $this->items,
            'name' => $this->name,
            'category' => $this->category,
            'sub_category' => $this->sub_category,
            'room' => $this->room,
            'unit' => $this->unit_data,
            'condition_placement' => $this->condition_placement,
            'condition_return' => $this->condition_return,
            'placement_date' => $this->placement_date,
            'return_date' => $this->return_date,
            'description' => $this->description
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }
}