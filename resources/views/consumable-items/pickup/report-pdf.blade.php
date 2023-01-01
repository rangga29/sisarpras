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
                        <a href="{{ route('consumable-items', $unit->slug) }}">
                            Barang Habis Pakai
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{ route('consumable-items.pickup-items', $unit->slug) }}">Data Pengambilan</a>
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
                            <div class="tab-title fw-semibold">DATA TERTENTU [TANGGAL PENGAMBILAN]</div>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content py-3">
                <div class="tab-pane fade show active" id="semua-data" role="tabpanel">
                    <form action="{{ route('consumable-items.pickup-items.report.pdf.print', [$unit->slug, 'all']) }}" method="POST" class="forms-sample">
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
                                        <input type="hidden" name="consumer" id="consumer" value="0">
                                        <input type="checkbox" class="form-check-input" name="consumer" id="all-consumer" value="1">
                                        <label class="form-check-label" for="all-consumer">
                                            Nama Pengguna
                                        </label>
                                    </div>
                                </div>
                                <div class="row col-sm-12 mx-auto">
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="unit_data" id="unit_data" value="0">
                                        <input type="checkbox" class="form-check-input" name="unit_data" id="all-unit-data" value="1">
                                        <label class="form-check-label" for="all-unit-data">
                                            Unit
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline">
                                        <input type="hidden" name="date" id="date" value="0">
                                        <input type="checkbox" class="form-check-input" name="date" id="all-date" value="1">
                                        <label class="form-check-label" for="all-date">
                                            Tanggal Pengambilan
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline">
                                        <input type="hidden" name="amount" id="amount" value="0">
                                        <input type="checkbox" class="form-check-input" name="amount" id="all-amount" value="1">
                                        <label class="form-check-label" for="all-amount">
                                            Jumlah Barang
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
                    <form action="{{ route('consumable-items.pickup-items.report.pdf.print', [$unit->slug, 'separate']) }}" method="POST" class="forms-sample">
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
                                        <input type="hidden" name="consumer" id="consumer" value="0">
                                        <input type="checkbox" class="form-check-input" name="consumer" id="separate-consumer" value="1">
                                        <label class="form-check-label" for="separate-consumer">
                                            Nama Pengguna
                                        </label>
                                    </div>
                                </div>
                                <div class="row col-sm-12 mx-auto">
                                    <div class="col form-check form-check-inline mb-3">
                                        <input type="hidden" name="unit_data" id="unit_data" value="0">
                                        <input type="checkbox" class="form-check-input" name="unit_data" id="separate-unit-data" value="1">
                                        <label class="form-check-label" for="separate-unit-data">
                                            Unit
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline">
                                        <input type="hidden" name="date" id="date" value="0">
                                        <input type="checkbox" class="form-check-input" name="date" id="separate-date" value="1">
                                        <label class="form-check-label" for="separate-date">
                                            Tanggal Pengambilan
                                        </label>
                                    </div>
                                    <div class="col form-check form-check-inline">
                                        <input type="hidden" name="amount" id="amount" value="0">
                                        <input type="checkbox" class="form-check-input" name="amount" id="separate-amount" value="1">
                                        <label class="form-check-label" for="separate-amount">
                                            Jumlah Barang
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
