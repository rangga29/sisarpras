@push('css-plugins')
<link href="{{ asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
@endpush

@push('js-plugins')
<script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/form-select2.js') }}"></script>
@endpush

@push('js-scripts')
<script>
    const name = document.querySelector('#sub_category_name');
    const slug = document.querySelector('#sub_category_slug');

    name.addEventListener('change', function() {
        fetch('/consumable-items/sub-categories/checkSlug?name=' + name.value)
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
                        <a href="{{ route('consumable-items', $unit->slug) }}">
                            Barang Habis Pakai
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{ route('consumable-items.sub-categories', $unit->slug) }}">Data Sub Kategori</a>
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
                                    <form class="row g-3" action="{{ route('consumable-items.sub-categories.store', $unit->slug) }}" method="POST">
                                        @csrf
                                        <div class="col-12">
                                            <label for="cons_category_id" class="form-label">Kategori</label>
                                            <select class="form-select single-select" name="cons_category_id" data-width="100%">
                                                @foreach ($categories as $category)
                                                @if(old('cons_category_id') == $category->id)
                                                <option value="{{ $category->id }}" selected>
                                                    {{ $category->category_name}}
                                                </option>
                                                @else
                                                <option value="{{ $category->id }}">
                                                    {{ $category->category_name}}
                                                </option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="sub_category_name" class="form-label">Nama Sub Kategori</label>
                                            <input type="text" class="form-control @error('sub_category_name') is-invalid @enderror" name="sub_category_name"
                                                id="sub_category_name" placeholder="Nama Sub Kategori" value="{{ old('sub_category_name') }}">
                                            @error('sub_category_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="sub_category_slug" class="form-label">Slug Sub Kategori</label>
                                            <input type="text" class="form-control @error('sub_category_slug') is-invalid @enderror" name="sub_category_slug"
                                                id="sub_category_slug" placeholder="Slug Sub Kategori" value="{{ old('sub_category_slug') }}" readonly>
                                            @error('sub_category_slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-success fw-semibold">SIMPAN</button>
                                            <a href="{{ route('consumable-items.sub-categories', $unit->slug) }}" class="btn btn-secondary fw-semibold">
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
