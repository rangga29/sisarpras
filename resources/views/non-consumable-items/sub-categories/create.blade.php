@push('js-scripts')
<script>
    const name = document.querySelector('#sub_category_name');
    const slug = document.querySelector('#sub_category_slug');

    name.addEventListener('change', function() {
        fetch('/non-consumable-items/sub-categories/checkSlug?name=' + name.value)
            .then(response => response.json())
            .then(data => slug.value = data.sub_category_slug)
    });
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
                        <a href="{{ route('non-consumable-items.sub-categories', $unit->slug) }}">Data Sub Kategori</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card shadow-none bg-light border">
                                <div class="card-body">
                                    <form class="row g-3" action="{{ route('non-consumable-items.sub-categories.store', $unit->slug) }}" method="POST">
                                        @csrf
                                        <div class="col-12 col-lg-6">
                                            <label for="non_cons_category_id" class="form-label">Kategori</label>
                                            <select class="form-select" name="non_cons_category_id" data-width="100%">
                                                @foreach ($categories as $category)
                                                @if(old('non_cons_category_id') == $category->id)
                                                <option value="{{ $category->id }}" selected>
                                                    [{{ $category->category_code }}] {{ $category->category_name}}
                                                </option>
                                                @else
                                                <option value="{{ $category->id }}">
                                                    [{{ $category->category_code }}] {{ $category->category_name}}
                                                </option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="sub_category_code" class="form-label">Kode Sub Kategori</label>
                                            <input type="text" class="form-control @error('sub_category_code') is-invalid @enderror" name="sub_category_code"
                                                placeholder="Kode Sub Kategori" value="{{ old('sub_category_code') }}">
                                            @error('sub_category_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="sub_category_name" class="form-label">Nama Sub Kategori</label>
                                            <input type="text" class="form-control @error('sub_category_name') is-invalid @enderror" name="sub_category_name"
                                                id="sub_category_name" placeholder="Nama Sub Kategori" value="{{ old('sub_category_name') }}">
                                            @error('sub_category_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="sub_category_slug" class="form-label">Slug Sub Kategori</label>
                                            <input type="text" class="form-control @error('sub_category_slug') is-invalid @enderror" name="sub_category_slug"
                                                id="sub_category_slug" placeholder="Slug Sub Kategori" value="{{ old('sub_category_slug') }}" readonly>
                                            @error('sub_category_slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-success fw-semibold">SIMPAN</button>
                                            <a href="{{ route('non-consumable-items.sub-categories', $unit->slug) }}" class="btn btn-secondary fw-semibold">
                                                KEMBALI
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
