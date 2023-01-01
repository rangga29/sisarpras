@push('css-plugins')
<link href="{{ asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
@endpush

@push('js-plugins')
<script src="{{ asset('js/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/form-select2.js') }}"></script>
@endpush

@push('js-scripts')
<script>
    (function($) {
    'use strict';
        $(":input").inputmask();
    })(jQuery);
</script>
@endpush

<x-layout>
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3 fw-semibold">Print Laporan [PDF]</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">{{ $unit->code }}</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{ route('consumable-items', $unit->slug) }}">
                            Barang Habis Pakai
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <hr />
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs nav-success nav-justified" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#semua-data" role="tab" aria-selected="true">
                        <div class="d-flex align-items-center">
                            <div class="tab-title fw-semibold">SEMUA DATA</div>
                        </div>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#data-tertentu" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-title fw-semibold">DATA TERTENTU [TANGGAL PEMBELIAN]</div>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content py-3">
                <div class="tab-pane fade show active" id="semua-data" role="tabpanel">
                    <form action="{{ route('consumable-items.report.pdf.print', [$unit->slug, 'all']) }}" method="POST" class="forms-sample">
                        @csrf
                        <div class="row mb-3">
                            <label for="all_filter" class="col-sm-2 col-form-label">Filter Stok</label>
                            <div class="col-sm-10">
                                <select class="form-select single-select" name="all_filter" id="all_filter" data-width="100%">
                                    <option value="filter-none">Tanpa Filter</option>
                                    <option value="filter-stok-ada">Filter : Stok Ada</option>
                                    <option value="filter-stok-habis">Filter : Stok Habis</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <label for="options" class="col-sm-2 col-form-label">Pilihan Kolom</label>
                            <div class="col-sm-10">
                                <div class="row col-sm-12 mx-auto">
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="name" id="name" value="0">
                                        <input type="checkbox" class="form-check-input" name="name" id="all-name" value="1">
                                        <label class="form-check-label" for="all-name">
                                            Nama Barang
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="category" id="category" value="0">
                                        <input type="checkbox" class="form-check-input" name="category" id="all-category" value="1">
                                        <label class="form-check-label" for="all-category">
                                            Kategori
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="sub_category" id="sub_category" value="0">
                                        <input type="checkbox" class="form-check-input" name="sub_category" id="all-sub-category" value="1">
                                        <label class="form-check-label" for="all-sub-category">
                                            Sub Kategori
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="brand" id="brand" value="0">
                                        <input type="checkbox" class="form-check-input" name="brand" id="all-brand" value="1">
                                        <label class="form-check-label" for="all-brand">
                                            Merk
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="shop" id="shop" value="0">
                                        <input type="checkbox" class="form-check-input" name="shop" id="all-shop" value="1">
                                        <label class="form-check-label" for="all-shop">
                                            Vendor
                                        </label>
                                    </div>
                                </div>
                                <div class="row col-sm-12 mx-auto">
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="fund" id="fund" value="0">
                                        <input type="checkbox" class="form-check-input" name="fund" id="all-fund" value="1">
                                        <label class="form-check-label" for="all-fund">
                                            Sumber Dana
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="room" id="room" value="0">
                                        <input type="checkbox" class="form-check-input" name="room" id="all-room" value="1">
                                        <label class="form-check-label" for="all-room">
                                            Ruangan Penyimpanan
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="unit_data" id="unit_data" value="0">
                                        <input type="checkbox" class="form-check-input" name="unit_data" id="all-unit-data" value="1">
                                        <label class="form-check-label" for="all-unit-data">
                                            Unit
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="price" id="price" value="0">
                                        <input type="checkbox" class="form-check-input" name="price" id="all-price" value="1">
                                        <label class="form-check-label" for="all-price">
                                            Harga
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="purchase_date" id="purchase_date" value="0">
                                        <input type="checkbox" class="form-check-input" name="purchase_date" id="all-purchase-date" value="1">
                                        <label class="form-check-label" for="all-purchase-date">
                                            Tanggal Pembelian
                                        </label>
                                    </div>
                                </div>
                                <div class="row col-sm-12 mx-auto">
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="initial_amount" id="initial_amount" value="0">
                                        <input type="checkbox" class="form-check-input" name="initial_amount" id="all-initial-amount" value="1">
                                        <label class="form-check-label" for="all-initial-amount">
                                            Jumlah Awal
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="taken_amount" id="taken_amount" value="0">
                                        <input type="checkbox" class="form-check-input" name="taken_amount" id="all-taken-amount" value="1">
                                        <label class="form-check-label" for="all-taken-amount">
                                            Jumlah Diambil
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline">
                                        <input type="hidden" name="stock_amount" id="stock_amount" value="0">
                                        <input type="checkbox" class="form-check-input" name="stock_amount" id="all-stock-amount" value="1">
                                        <label class="form-check-label" for="all-stock-amount">
                                            Jumlah Stok
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline">
                                        <input type="hidden" name="description" id="description" value="0">
                                        <input type="checkbox" class="form-check-input" name="description" id="all-description" value="1">
                                        <label class="form-check-label" for="all-description">
                                            Keterangan
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline">
                                        <input type="hidden" name="insert_date" id="insert_date" value="0">
                                        <input type="checkbox" class="form-check-input" name="insert_date" id="all-insert-date" value="1">
                                        <label class="form-check-label" for="all-insert-date">
                                            Tanggal Tambah Data
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success me-2 fw-bolder">PRINT LAPORAN</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="data-tertentu" role="tabpanel">
                    <form action="{{ route('consumable-items.report.pdf.print', [$unit->slug, 'separate']) }}" method="POST" class="forms-sample">
                        @csrf
                        <div class="row mb-3">
                            <label for="separate_filter" class="col-sm-2 col-form-label">Filter Stok</label>
                            <div class="col-sm-10">
                                <select class="form-select single-select" name="separate_filter" id="separate_filter" data-width="100%">
                                    <option value="filter-none">Tanpa Filter</option>
                                    <option value="filter-stok-ada">Filter : Stok Ada</option>
                                    <option value="filter-stok-habis">Filter : Stok Habis</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="firstDate" class="col-sm-2 col-form-label">Tanggal Awal</label>
                            <div class="col-sm-10">
                                <input class="form-control" name="firstDate" id="firstDate" placeholder="Tanggal Awal" value="{{ old('firstDate') }}"
                                    data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="dd/mm/yyyy" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="lastDate" class="col-sm-2 col-form-label">Tanggal Akhir</label>
                            <div class="col-sm-10">
                                <input class="form-control" name="lastDate" id="lastDate" placeholder="Tanggal Awal" value="{{ old('lastDate') }}"
                                    data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="dd/mm/yyyy" required>
                            </div>
                        </div>
                        <div class="row">
                            <label for="options" class="col-sm-2 col-form-label">Pilihan Kolom</label>
                            <div class="col-sm-10">
                                <div class="row col-sm-12 mx-auto">
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="name" id="name" value="0">
                                        <input type="checkbox" class="form-check-input" name="name" id="separate-name" value="1">
                                        <label class="form-check-label" for="separate-name">
                                            Nama Barang
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="category" id="category" value="0">
                                        <input type="checkbox" class="form-check-input" name="category" id="separate-category" value="1">
                                        <label class="form-check-label" for="separate-category">
                                            Kategori
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="sub_category" id="sub_category" value="0">
                                        <input type="checkbox" class="form-check-input" name="sub_category" id="separate-sub-category" value="1">
                                        <label class="form-check-label" for="separate-sub-category">
                                            Sub Kategori
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="brand" id="brand" value="0">
                                        <input type="checkbox" class="form-check-input" name="brand" id="separate-brand" value="1">
                                        <label class="form-check-label" for="separate-brand">
                                            Merk
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="shop" id="shop" value="0">
                                        <input type="checkbox" class="form-check-input" name="shop" id="separate-shop" value="1">
                                        <label class="form-check-label" for="separate-shop">
                                            Vendor
                                        </label>
                                    </div>
                                </div>
                                <div class="row col-sm-12 mx-auto">
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="fund" id="fund" value="0">
                                        <input type="checkbox" class="form-check-input" name="fund" id="separate-fund" value="1">
                                        <label class="form-check-label" for="separate-fund">
                                            Sumber Dana
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="room" id="room" value="0">
                                        <input type="checkbox" class="form-check-input" name="room" id="separate-room" value="1">
                                        <label class="form-check-label" for="separate-room">
                                            Ruangan Penyimpanan
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="unit_data" id="unit_data" value="0">
                                        <input type="checkbox" class="form-check-input" name="unit_data" id="separate-unit-data" value="1">
                                        <label class="form-check-label" for="separate-unit-data">
                                            Unit
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="price" id="price" value="0">
                                        <input type="checkbox" class="form-check-input" name="price" id="separate-price" value="1">
                                        <label class="form-check-label" for="separate-price">
                                            Harga
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="purchase_date" id="purchase_date" value="0">
                                        <input type="checkbox" class="form-check-input" name="purchase_date" id="separate-purchase-date" value="1">
                                        <label class="form-check-label" for="separate-purchase-date">
                                            Tanggal Pembelian
                                        </label>
                                    </div>
                                </div>
                                <div class="row col-sm-12 mx-auto">
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="initial_amount" id="initial_amount" value="0">
                                        <input type="checkbox" class="form-check-input" name="initial_amount" id="separate-initial-amount" value="1">
                                        <label class="form-check-label" for="separate-initial-amount">
                                            Jumlah Awal
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="taken_amount" id="taken_amount" value="0">
                                        <input type="checkbox" class="form-check-input" name="taken_amount" id="separate-taken-amount" value="1">
                                        <label class="form-check-label" for="separate-taken-amount">
                                            Jumlah Diambil
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline">
                                        <input type="hidden" name="stock_amount" id="stock_amount" value="0">
                                        <input type="checkbox" class="form-check-input" name="stock_amount" id="separate-stock-amount" value="1">
                                        <label class="form-check-label" for="separate-stock-amount">
                                            Jumlah Stok
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline">
                                        <input type="hidden" name="description" id="description" value="0">
                                        <input type="checkbox" class="form-check-input" name="description" id="separate-description" value="1">
                                        <label class="form-check-label" for="separate-description">
                                            Keterangan
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline">
                                        <input type="hidden" name="insert_date" id="insert_date" value="0">
                                        <input type="checkbox" class="form-check-input" name="insert_date" id="separate-insert-date" value="1">
                                        <label class="form-check-label" for="separate-insert-date">
                                            Tanggal Tambah Data
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success me-2 fw-bolder">PRINT LAPORAN</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-layout>
