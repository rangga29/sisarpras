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
        <div class="breadcrumb-title pe-3 fw-semibold">Tambah Data</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
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
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <form class="card" action="{{ route('consumable-items.pickup-items.create', $unit->slug) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card shadow-none bg-light border">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-lg-6">
                                            <label for="consumer_id" class="form-label">Nama Pengguna</label>
                                            <select class="form-select single-select" name="consumer_id" data-width="100%">
                                                @foreach ($consumers as $consumer)
                                                @if(old('consumer_id') == $consumer->id)
                                                <option value="{{ $consumer->id }}" selected>{{ $consumer->name }} - {{ $consumer->position->name }}</option>
                                                @else
                                                <option value="{{ $consumer->id }}">{{ $consumer->name }} - {{ $consumer->position->name }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="cons_item_id" class="form-label">Nama Barang</label>
                                            <select class="form-select single-select" name="cons_item_id" data-width="100%">
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
                                        <div class="col-12 col-lg-6">
                                            <label for="pickup_date" class="form-label">Tanggal Ambil</label>
                                            <input class="form-control @error('pickup_date') is-invalid @enderror" name="pickup_date"
                                                placeholder="Tanggal Ambil" value="{{ old('pickup_date') }}" data-inputmask="'alias': 'datetime'"
                                                data-inputmask-inputformat="dd/mm/yyyy">
                                            @error('pickup_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="amount" class="form-label">Jumlah Ambil</label>
                                            <input type="number" class="form-control @error('amount') is-invalid @enderror" name="amount"
                                                placeholder="Jumlah Ambil" value="{{ old('amount') }}">
                                            @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="description" class="form-label">Keterangan</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                                                placeholder="Keterangan" rows="3" cols="4">{{ old('description') }}</textarea>
                                        </div>
                                        <div class="d-sm-flex align-items-center">
                                            <div class="ms-auto">
                                                <button type="submit" class="btn btn-success fw-semibold">SIMPAN</button>
                                                <a href="{{ route('consumable-items.pickup-items', $unit->slug) }}"
                                                    class="btn btn-secondary fw-semibold">KEMBALI</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layout>
