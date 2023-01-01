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
        <div class="breadcrumb-title pe-3 fw-semibold">Data Peminjaman</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">{{ $unit->code }}</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{ route('non-consumable-items', $unit->slug) }}">
                            Barang Tidak Habis Pakai
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('non-consumable-items.loan-items.create', $unit->slug) }}" class="btn btn-success">Tambah Data</a>
                <button type="button" class="btn btn-success split-bg-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"></button>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                    <a class="dropdown-item" href="{{ route('non-consumable-items.loan-items.report.pdf', $unit->slug) }}">Laporan PDF</a>
                    <a class="dropdown-item" href="{{ route('non-consumable-items.loan-items.report.excel', $unit->slug) }}">Laporan Excel</a>
                </div>
            </div>
        </div>
    </div>
    <hr />
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs nav-primary" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#belum-kembali" role="tab" aria-selected="true">
                        <div class="d-flex align-items-center">
                            <div class="tab-title fw-semibold">BELUM KEMBALI</div>
                        </div>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#sudah-kembali" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-title fw-semibold">SUDAH KEMBALI</div>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content py-3">
                <div class="tab-pane fade show active" id="belum-kembali" role="tabpanel">
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-striped align-middle" style="width:100%">
                            <thead class="table-secondary">
                                <tr>
                                    <th>#</th>
                                    <th>KODE</th>
                                    <th>NAMA PEMINJAM</th>
                                    <th>NAMA BARANG</th>
                                    <th>KATEGORI</th>
                                    <th>TANGGAL PINJAM</th>
                                    <th>KONDISI PINJAM</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($loans as $loan)
                                @if ($loan->unit_id == $unit->id)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <h6 class="mb-0 product-title text-black" data-bs-toggle="tooltip" data-bs-placement="left"
                                            title="{{ $loan->description }}">
                                            {{$loan->non_cons_item->non_cons_sub_category->non_cons_category->category_code }}.{{
                                            $loan->non_cons_item->non_cons_sub_category->sub_category_code }}.{{ $loan->non_cons_item->item_number }}
                                        </h6>
                                    </td>
                                    <td>{{ $loan->consumer->name }}</td>
                                    <td>{{ $loan->non_cons_item->name }}</td>
                                    <td>{{ $loan->non_cons_item->non_cons_sub_category->non_cons_category->category_name }} - {{
                                        $loan->non_cons_item->non_cons_sub_category->sub_category_name }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $loan->loan_date)->isoFormat('DD MMMM Y') }}</td>
                                    <td>{{ $loan->loan_condition->name }}</td>
                                    <td>
                                        <div class="table-actions d-flex align-items-center gap-3 fs-4">
                                            <a href="{{ route('non-consumable-items.loan-items.return.create', [$unit->slug, $loan->loan_code]) }}"
                                                class="text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Pengembalian">
                                                <i class="bi bi-arrow-return-left"></i>
                                            </a>
                                            <a href="{{ route('non-consumable-items.loan-items.edit', [$unit->slug, $loan->loan_code]) }}" class="text-warning"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah Data">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <form action="{{ route('non-consumable-items.loan-items.delete', [$unit->slug, $loan->loan_code]) }}" method="POST">
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
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="sudah-kembali" role="tabpanel">
                    <div class="table-responsive">
                        <table id="dataTable2" class="table table-striped align-middle" style="width:100%">
                            <thead class="table-secondary">
                                <tr>
                                    <th>#</th>
                                    <th>KODE</th>
                                    <th>NAMA PEMINJAM</th>
                                    <th>NAMA BARANG</th>
                                    <th>KATEGORI</th>
                                    <th>TANGGAL PINJAM</th>
                                    <th>KONDISI PINJAM</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($return_loans as $loan)
                                @if ($loan->unit_id == $unit->id)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <h6 class="mb-0 product-title text-black" data-bs-toggle="tooltip" data-bs-placement="left"
                                            title="{{ $loan->description }}">
                                            {{$loan->non_cons_item->non_cons_sub_category->non_cons_category->category_code }}.{{
                                            $loan->non_cons_item->non_cons_sub_category->sub_category_code }}.{{ $loan->non_cons_item->item_number }}
                                        </h6>
                                    </td>
                                    <td>{{ $loan->consumer->name }}</td>
                                    <td>{{ $loan->non_cons_item->name }}</td>
                                    <td>{{ $loan->non_cons_item->non_cons_sub_category->non_cons_category->category_name }} - {{
                                        $loan->non_cons_item->non_cons_sub_category->sub_category_name }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $loan->loan_date)->isoFormat('DD MMMM Y') }}</td>
                                    <td>{{ $loan->loan_condition->name }}</td>
                                    <td>
                                        <div class="table-actions d-flex align-items-center gap-3 fs-4">
                                            <a href="{{ route('non-consumable-items.loan-items.return.edit', [$unit->slug, $loan->loan_code]) }}"
                                                class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah Data Pengembalian">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
