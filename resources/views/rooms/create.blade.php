@push('js-scripts')
<script>
    const name = document.querySelector('#name');
    const slug = document.querySelector('#slug');

    name.addEventListener('change', function() {
        fetch('/rooms/checkSlug?name=' + name.value)
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
                    <li class="breadcrumb-item">{{ $unit->code }}</li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('rooms', $unit->slug) }}">Data Ruangan</a></li>
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
                                    <form class="row g-3" action="{{ route('rooms.store', $unit->slug) }}" method="POST">
                                        @csrf
                                        <div class="col-12">
                                            <label for="name" class="form-label">Nama Ruangan</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"
                                                placeholder="Nama Ruangan" value="{{ old('name') }}">
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="slug" class="form-label">Slug Ruangan</label>
                                            <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" id="slug"
                                                placeholder="Slug Ruangan" value="{{ old('slug') }}" readonly>
                                            @error('slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="unit_id" class="form-label">Unit</label>
                                            <select class="form-select" name="unit_id" data-width="100%">
                                                <option value="{{ $unit->id }}" selected>{{ $unit->name }}</option>
                                            </select>
                                        </div>
                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-success fw-semibold">SIMPAN</button>
                                            <a href="{{ route('rooms', $unit->slug) }}" class="btn btn-secondary fw-semibold">KEMBALI</a>
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
