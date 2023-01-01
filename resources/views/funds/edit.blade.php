@push('js-scripts')
<script>
    const name = document.querySelector('#name');
    const slug = document.querySelector('#slug');

    name.addEventListener('change', function() {
        fetch('/funds/checkSlug?name=' + name.value)
            .then(response => response.json())
            .then(data => slug.value = data.slug)
    });
</script>
@endpush

<x-layout>
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3 fw-semibold">Ubah Data - {{ $fund->name }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item">Data Umum</li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('funds') }}">Data Sumber Dana</a></li>
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
                                    <form class="row g-3" action="{{ route('funds.update', $fund->slug) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="col-12">
                                            <label for="name" class="form-label">Nama Sumber Dana</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"
                                                placeholder="Nama Sumber Dana" value="{{ old('name', $fund->name) }}">
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="slug" class="form-label">Slug Sumber Dana</label>
                                            <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" id="slug"
                                                placeholder="Slug Sumber Dana" value="{{ old('slug', $fund->slug) }}" readonly>
                                            @error('slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-warning fw-semibold">SIMPAN</button>
                                            <a href="{{ route('funds') }}" class="btn btn-secondary fw-semibold">KEMBALI</a>
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
