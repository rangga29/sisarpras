@push('css-plugins')
<link rel="stylesheet" href="{{ asset('plugins/dropify/css/dropify.min.css') }}">
@endpush

@push('js-plugins')
<script src="{{ asset('js/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('plugins/dropify/js/dropify.min.js') }}"></script>
<script src="{{ asset('js/dropify.js') }}"></script>
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
        <div class="breadcrumb-title pe-3 fw-semibold">Ubah Data - {{ $item->name }}</div>
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
                        <a href="{{ route('non-consumable-items', $unit->slug) }}">Data Barang</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <form class="card" action="{{ route('non-consumable-items.update', [$unit->slug, $item->item_code]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-7">
                            <div class="card shadow-none bg-light border">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-lg-8">
                                            <label for="name" class="form-label">Nama Barang</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"
                                                placeholder="Nama Barang" value="{{ old('name', $item->name) }}">
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="item_number" class="form-label">Nomor Barang</label>
                                            <input type="text" class="form-control @error('item_number') is-invalid @enderror" name="item_number"
                                                placeholder="Nomor Barang" value="{{ old('item_number', $item->item_number) }}">
                                            @error('item_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="price" class="form-label">Harga</label>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" placeholder="Harga"
                                                value="{{ old('price', $item->price) }}">
                                            @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="purchase_date" class="form-label">Tanggal Beli</label>
                                            <input class="form-control @error('purchase_date') is-invalid @enderror" name="purchase_date"
                                                placeholder="Tanggal Beli" value="{{ old('purchase_date', $item->purchase_date) }}"
                                                data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="dd/mm/yyyy">
                                            @error('purchase_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-lg-8">
                                            <label for="image" class="form-label">Foto</label>
                                            <input type="file" class="form-control dropify" name="image" data-height="200" />
                                            @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="image" class="form-label">Foto Lama</label><br>
                                            <img src="{{ asset('storage/non-consumable-items/'. $item->image) }}" alt="{{ $item->item_code }}"
                                                style="width: 280px">
                                        </div>
                                        <div class="col-12 col-lg-8">
                                            <label for="receipt" class="form-label">Tanda Terima</label>
                                            <input type="file" class="form-control @error('receipt') is-invalid @enderror" name="receipt"
                                                accept="application/pdf">
                                            @error('receipt')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label for="image" class="form-label"></label><br>
                                            <a href="{{ asset('storage/non-consumable-items/' . $item->receipt) }}" target="_BLANK"
                                                class="btn btn-secondary mt-2">
                                                Tanda Terima Barang
                                            </a>
                                        </div>
                                        <div class="col-12">
                                            <label for="include" class="form-label">Kelengkapan</label>
                                            <textarea class="form-control @error('include') is-invalid @enderror" name="include" placeholder="Kelengkapan"
                                                rows="3" cols="4">{{ old('include', $item->include) }}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <label for="description" class="form-label">Keterangan</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                                                placeholder="Keterangan" rows="3" cols="4">{{ old('description', $item->description) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-5">
                            <div class="card shadow-none bg-light border">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="non_cons_sub_category_id" class="form-label">Sub Kategori</label>
                                            <select class="form-select" name="non_cons_sub_category_id" data-width="100%">
                                                @foreach ($sub_categories as $sub)
                                                @if(old('non_cons_sub_category_id', $item->non_cons_sub_category_id) == $sub->sc_id)
                                                <option value="{{ $sub->sc_id }}" selected>
                                                    [{{ $sub->c_code }}.{{$sub->sc_code }}]
                                                    {{ $sub->c_name}} - {{ $sub->sc_name }}
                                                </option>
                                                @else
                                                <option value="{{ $sub->sc_id }}">
                                                    [{{ $sub->c_code }}.{{$sub->sc_code }}]
                                                    {{ $sub->c_name}} - {{ $sub->sc_name }}
                                                </option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="brand_id" class="form-label">Merk</label>
                                            <select class="form-select" name="brand_id" data-width="100%">
                                                @foreach ($brands as $brand)
                                                @if(old('brand_id', $item->brand_id) == $brand->id)
                                                <option value="{{ $brand->id }}" selected>{{ $brand->name }}</option>
                                                @else
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="shop_id" class="form-label">Vendor</label>
                                            <select class="form-select" name="shop_id" data-width="100%">
                                                @foreach ($shops as $shop)
                                                @if(old('shop_id', $item->shop_id) == $shop->id)
                                                <option value="{{ $shop->id }}" selected>{{ $shop->name }}</option>
                                                @else
                                                <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="fund_id" class="form-label">Sumber Dana</label>
                                            <select class="form-select" name="fund_id" data-width="100%">
                                                @foreach ($funds as $fund)
                                                @if(old('fund_id', $item->fund_id) == $fund->id)
                                                <option value="{{ $fund->id }}" selected>{{ $fund->name }}</option>
                                                @else
                                                <option value="{{ $fund->id }}">{{ $fund->name }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="room_id" class="form-label">Ruang Penyimpanan</label>
                                            <select class="form-select" name="room_id" data-width="100%">
                                                @foreach ($rooms as $room)
                                                @if(old('room_id', $item->room_id) == $room->id)
                                                <option value="{{ $room->id }}" selected>{{ $room->name }}</option>
                                                @else
                                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="non_cons_condition_id" class="form-label">Kondisi</label>
                                            <select class="form-select" name="non_cons_condition_id" data-width="100%">
                                                @foreach ($conditions as $condition)
                                                @if(old('non_cons_condition_id', $item->non_cons_condition_id) == $condition->id)
                                                <option value="{{ $condition->id }}" selected>{{ $condition->name }}</option>
                                                @else
                                                <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="unit_id" class="form-label">Unit</label>
                                            <select class="form-select" name="unit_id" data-width="100%">
                                                <option value="{{ $unit->id }}" selected>{{ $unit->name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-3 bg-transparent">
                    <div class="d-sm-flex align-items-center">
                        <div class="ms-auto">
                            <button type="submit" class="btn btn-warning fw-semibold">SIMPAN</button>
                            <a href="{{ route('consumable-items', $unit->slug) }}" class="btn btn-secondary fw-semibold">KEMBALI</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layout>
