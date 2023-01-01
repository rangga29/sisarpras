@push('css-plugins')
<link rel="stylesheet" href="{{ asset('plugins/datatable/css/dataTables.bootstrap5.min.css') }}">
@endpush

@push('js-plugins')
<script src="{{ asset('plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('js/datatables.js') }}"></script>
@endpush

<x-layout>
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3 fw-semibold">Data Merk</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Data Umum</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('brands.create') }}" class="btn btn-success">Tambah Data</a>
            </div>
        </div>
    </div>
    <hr />
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped align-middle" style="width:100%">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>NAMA MERK</th>
                            <th>SLUG</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($brands as $brand)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3 cursor-pointer">
                                    <img src="{{ $brand->image === 'default-brand.png' ? asset('images/defaults/default-brand.png') : asset('storage/brands/'. $brand->image) }}"
                                        class="rounded-circle" width="46" height="46" alt="{{ $brand->slug }}">
                                    <div>
                                        <h6 class="mb-0 product-title text-black">{{ $brand->name }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $brand->slug }}</td>
                            <td>
                                <div class="table-actions d-flex align-items-center gap-3 fs-4">
                                    <a href="{{ route('brands.edit', $brand->slug) }}" class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        title="Ubah Data">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('brands.delete', $brand->slug) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="border-0 bg-transparent text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            title="Hapus Data" onclick="return confirm('Yakin Ingin Menghapus Data Ini?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
