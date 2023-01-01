@push('js-scripts')
<script>
    const name = document.querySelector('#category_name');
    const slug = document.querySelector('#category_slug');

    name.addEventListener('change', function() {
        fetch('/consumable-items/categories/checkSlug?name=' + name.value)
            .then(response => response.json())
            .then(data => slug.value = data.category_slug)
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
                        <a href="{{ route('consumable-items.categories', $unit->slug) }}">Data Kategori</a>
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
                                    <form class="row g-3" action="{{ route('consumable-items.categories.store', $unit->slug) }}" method="POST">
                                        @csrf
                                        <div class="col-12">
                                            <label for="category_name" class="form-label">Nama Kategori</label>
                                            <input type="text" class="form-control @error('category_name') is-invalid @enderror" name="category_name"
                                                id="category_name" placeholder="Nama Kategori" value="{{ old('category_name') }}">
                                            @error('category_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="category_slug" class="form-label">Slug Kategori</label>
                                            <input type="text" class="form-control @error('category_slug') is-invalid @enderror" name="category_slug"
                                                id="category_slug" placeholder="Slug Kategori" value="{{ old('category_slug') }}" readonly>
                                            @error('category_slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-success fw-semibold">SIMPAN</button>
                                            <a href="{{ route('consumable-items.categories', $unit->slug) }}" class="btn btn-secondary fw-semibold">KEMBALI</a>
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
