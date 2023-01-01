@push('css-plugins')
<link rel="stylesheet" href="{{ asset('plugins/dropify/css/dropify.min.css') }}">
@endpush

@push('js-plugins')
<script src="{{ asset('plugins/dropify/js/dropify.min.js') }}"></script>
<script src="{{ asset('js/dropify.js') }}"></script>
@endpush

@push('js-scripts')
<script>
    const name = document.querySelector('#name');
    const slug = document.querySelector('#slug');

    name.addEventListener('change', function() {
        fetch('/shops/checkSlug?name=' + name.value)
            .then(response => response.json())
            .then(data => slug.value = data.slug)
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
                    <li class="breadcrumb-item">Data Umum</li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('shops') }}">Data Vendor</a></li>
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
                                    <form class="row g-3" action="{{ route('shops.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-12">
                                            <label for="name" class="form-label">Nama Vendor</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"
                                                placeholder="Nama Vendor" value="{{ old('name') }}">
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="slug" class="form-label">Slug Vendor</label>
                                            <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" id="slug"
                                                placeholder="Slug Vendor" value="{{ old('slug') }}" readonly>
                                            @error('slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="image" class="form-label">Logo</label>
                                            <input type="file" class="form-control dropify" name="image" data-height="300" />
                                            @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-success fw-semibold">SIMPAN</button>
                                            <a href="{{ route('shops') }}" class="btn btn-secondary fw-semibold">KEMBALI</a>
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
