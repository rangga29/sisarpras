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
        <div class="breadcrumb-title pe-3 fw-semibold">Data Sumber Dana</div>
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
                <a href="{{ route('funds.create') }}" class="btn btn-success">Tambah Data</a>
                <button type="button" class="btn btn-success split-bg-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"></button>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                    <a class="dropdown-item" href="javascript:;">Laporan PDF</a>
                    <a class="dropdown-item" href="javascript:;">Laporan Excel</a>
                </div>
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
                            <th>NAMA SUMBER DANA</th>
                            <th>SLUG</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($funds as $fund)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('funds.show', $fund->slug) }}">
                                    <h6 class="mb-0 product-title text-black">{{ $fund->name }}</h6>
                                </a>
                            </td>
                            <td>{{ $fund->slug }}</td>
                            <td>
                                <div class="table-actions d-flex align-items-center gap-3 fs-4">
                                    <a href="{{ route('funds.edit', $fund->slug) }}" class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        title="Ubah Data">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('funds.delete', $fund->slug) }}" method="POST">
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
