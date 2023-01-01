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
                    <li class="breadcrumb-item">
                        <a href="{{ route('non-consumable-items', $unit->slug) }}">
                            Barang Tidak Habis Pakai
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{ route('non-consumable-items.placement-items', $unit->slug) }}">Data Penempatan</a>
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
                            <div class="tab-title fw-semibold">DATA TERTENTU [TANGGAL PENEMPATAN]</div>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content py-3">
                <div class="tab-pane fade show active" id="semua-data" role="tabpanel">
                    <form action="{{ route('non-consumable-items.placement-items.report.pdf.print', [$unit->slug, 'all']) }}" method="POST"
                        class="forms-sample">
                        @csrf
                        <div class="row mb-3">
                            <label for="all_item_name" class="col-sm-2 col-form-label">Nama Barang</label>
                            <div class="col-sm-10">
                                <select class="form-select single-select" name="all_item_name" id="all_item_name" data-width="100%">
                                    <option value="" selected>Tanpa Filter</option>
                                    @foreach ($items as $item)
                                    @if(old('cons_item_id') == $item->i_id)
                                    <option value="{{ $item->i_id }}" selected>
                                        [{{ $item->c_name }} - {{$item->sc_name }}] {{ $item->i_name }}
                                    </option>
                                    @else
                                    <option value="{{ $item->i_id }}">
                                        [{{ $item->c_name }} - {{$item->sc_name }}] {{ $item->i_name }}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="all_filter" class="col-sm-2 col-form-label">Filter Pengembalian</label>
                            <div class="col-sm-10">
                                <select class="form-select single-select" name="all_filter" id="all_filter" data-width="100%">
                                    <option value="filter-none">Tanpa Filter</option>
                                    <option value="filter-belum-kembali">Belum Kembali</option>
                                    <option value="filter-sudah-kembali">Sudah Kembali</option>
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
                                        <input type="hidden" name="room" id="room" value="0">
                                        <input type="checkbox" class="form-check-input" name="room" id="all-room" value="1">
                                        <label class="form-check-label" for="all-room">
                                            Nama Ruangan
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="unit_data" id="unit_data" value="0">
                                        <input type="checkbox" class="form-check-input" name="unit_data" id="all-unit-data" value="1">
                                        <label class="form-check-label" for="all-unit-data">
                                            Unit
                                        </label>
                                    </div>
                                </div>
                                <div class="row col-sm-12 mx-auto">
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="condition_placement" id="condition_placement" value="0">
                                        <input type="checkbox" class="form-check-input" name="condition_placement" id="all-condition-placement" value="1">
                                        <label class="form-check-label" for="all-condition-placement">
                                            Kondisi Pinjam
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="condition_return" id="condition_return" value="0">
                                        <input type="checkbox" class="form-check-input" name="condition_return" id="all-condition-return" value="1">
                                        <label class="form-check-label" for="all-condition-return">
                                            Kondisi Kembali
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline">
                                        <input type="hidden" name="placement_date" id="placement_date" value="0">
                                        <input type="checkbox" class="form-check-input" name="placement_date" id="all-placement-date" value="1">
                                        <label class="form-check-label" for="all-placement-date">
                                            Tanggal Pinjam
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline">
                                        <input type="hidden" name="return_date" id="return_date" value="0">
                                        <input type="checkbox" class="form-check-input" name="return_date" id="all-return-date" value="1">
                                        <label class="form-check-label" for="all-return-date">
                                            Tanggal Kembali
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline">
                                        <input type="hidden" name="description" id="description" value="0">
                                        <input type="checkbox" class="form-check-input" name="description" id="all-description" value="1">
                                        <label class="form-check-label" for="all-description">
                                            Keterangan
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
                    <form action="{{ route('non-consumable-items.placement-items.report.pdf.print', [$unit->slug, 'separate']) }}" method="POST"
                        class="forms-sample">
                        @csrf
                        <div class="row mb-3">
                            <label for="separate_item_name" class="col-sm-2 col-form-label">Nama Barang</label>
                            <div class="col-sm-10">
                                <select class="form-select single-select" name="separate_item_name" id="separate_item_name" data-width="100%">
                                    <option value="" selected>Tanpa Filter</option>
                                    @foreach ($items as $item)
                                    @if(old('cons_item_id') == $item->i_id)
                                    <option value="{{ $item->i_id }}" selected>
                                        [{{ $item->c_name }} - {{$item->sc_name }}] {{ $item->i_name }}
                                    </option>
                                    @else
                                    <option value="{{ $item->i_id }}">
                                        [{{ $item->c_name }} - {{$item->sc_name }}] {{ $item->i_name }}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="separate_filter" class="col-sm-2 col-form-label">Filter Pengembalian</label>
                            <div class="col-sm-10">
                                <select class="form-select single-select" name="separate_filter" id="separate_filter" data-width="100%">
                                    <option value="filter-none">Tanpa Filter</option>
                                    <option value="filter-belum-kembali">Belum Kembali</option>
                                    <option value="filter-sudah-kembali">Sudah Kembali</option>
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
                                        <input type="hidden" name="room" id="room" value="0">
                                        <input type="checkbox" class="form-check-input" name="room" id="separate-room" value="1">
                                        <label class="form-check-label" for="separate-room">
                                            Nama Ruangan
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="unit_data" id="unit_data" value="0">
                                        <input type="checkbox" class="form-check-input" name="unit_data" id="separate-unit-data" value="1">
                                        <label class="form-check-label" for="separate-unit-data">
                                            Unit
                                        </label>
                                    </div>
                                </div>
                                <div class="row col-sm-12 mx-auto">
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="condition_placement" id="condition_placement" value="0">
                                        <input type="checkbox" class="form-check-input" name="condition_placement" id="separate-condition-placement" value="1">
                                        <label class="form-check-label" for="separate-condition-placement">
                                            Kondisi Pinjam
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="condition_return" id="condition_return" value="0">
                                        <input type="checkbox" class="form-check-input" name="condition_return" id="separate-condition-return" value="1">
                                        <label class="form-check-label" for="separate-condition-return">
                                            Kondisi Kembali
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline">
                                        <input type="hidden" name="placement_date" id="placement_date" value="0">
                                        <input type="checkbox" class="form-check-input" name="placement_date" id="separate-placement-date" value="1">
                                        <label class="form-check-label" for="separate-placement-date">
                                            Tanggal Pinjam
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline">
                                        <input type="hidden" name="return_date" id="return_date" value="0">
                                        <input type="checkbox" class="form-check-input" name="return_date" id="separate-return-date" value="1">
                                        <label class="form-check-label" for="separate-return-date">
                                            Tanggal Kembali
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline">
                                        <input type="hidden" name="description" id="description" value="0">
                                        <input type="checkbox" class="form-check-input" name="description" id="separate-description" value="1">
                                        <label class="form-check-label" for="separate-description">
                                            Keterangan
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
