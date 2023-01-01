@push('js-plugins')
<script src="{{ asset('js/jquery.inputmask.min.js') }}"></script>
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
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <form class="card" action="{{ route('non-consumable-items.placement-items.store', $unit->slug) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card shadow-none bg-light border">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-lg-6">
                                            <label for="non_cons_item_id" class="form-label">Nama Barang</label>
                                            <select class="form-select" name="non_cons_item_id" data-width="100%">
                                                @foreach ($items as $item)
                                                @if(old('non_cons_item_id') == $item->i_id)
                                                <option value="{{ $item->i_id }}" selected>
                                                    [{{ $item->c_code }}.{{ $item->sc_code }}.{{ $item->i_number }}] {{ $item->c_name }} - {{ $item->sc_name }}
                                                    - {{ $item->i_name }}
                                                </option>
                                                @else
                                                <option value="{{ $item->i_id }}">
                                                    [{{ $item->c_code }}.{{ $item->sc_code }}.{{ $item->i_number }}] {{ $item->c_name }} - {{ $item->sc_name }}
                                                    - {{ $item->i_name }}
                                                </option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label for="room_id" class="form-label">Nama Ruangan</label>
                                            <select class="form-select" name="room_id" data-width="100%">
                                                @foreach ($rooms as $room)
                                                @if(old('room_id') == $room->id)
                                                <option value="{{ $room->id }}" selected>{{ $room->name }}</option>
                                                @else
                                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label for="placement_date" class="form-label">Tanggal Penempatan</label>
                                            <input class="form-control @error('placement_date') is-invalid @enderror" name="placement_date"
                                                placeholder="Tanggal Penempatan" value="{{ old('placement_date') }}" data-inputmask="'alias': 'datetime'"
                                                data-inputmask-inputformat="dd/mm/yyyy">
                                            @error('placement_date')
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
                                                <a href="{{ route('non-consumable-items.placement-items', $unit->slug) }}"
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
