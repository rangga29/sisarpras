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
        <div class="breadcrumb-title pe-3 fw-semibold">Data Barang</div>
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
                        <a href="{{ route('consumable-items', $unit->slug) }}">
                            Barang Habis Pakai
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('consumable-items.create', $unit->slug) }}" class="btn btn-success">Tambah Data</a>
                <button type="button" class="btn btn-success split-bg-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"></button>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                    <a class="dropdown-item" href="{{ route('consumable-items.report.pdf', $unit->slug) }}">Laporan PDF</a>
                    <a class="dropdown-item" href="{{ route('consumable-items.report.excel', $unit->slug) }}">Laporan Excel</a>
                </div>
            </div>
        </div>
    </div>
    <hr />
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs nav-primary" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#semua-data" role="tab" aria-selected="true">
                        <div class="d-flex align-items-center">
                            <div class="tab-title fw-semibold">SEMUA DATA</div>
                        </div>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#stok-habis" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-title fw-semibold">STOK HABIS</div>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content py-3">
                <div class="tab-pane fade show active" id="semua-data" role="tabpanel">
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-striped align-middle" style="width:100%">
                            <thead class="table-secondary">
                                <tr>
                                    <th>#</th>
                                    <th>NAMA BARANG</th>
                                    <th>KATEGORI</th>
                                    <th>MERK</th>
                                    <th>R. PENYIMPANAN</th>
                                    <th>TANGGAL BELI</th>
                                    <th>STOK</th>
                                    <th>DIAMBIL</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="productlist">
                                        <a class="d-flex align-items-center gap-2" href="{{ route('consumable-items.show', [$unit->slug, $item->item_code]) }}">
                                            <div class="product-box">
                                                <img src="{{ asset('storage/consumable-items/'. $item->image) }}" alt="{{ $item->item_code }}">
                                            </div>
                                            <div>
                                                <h6 class="mb-0 product-title">{{ $item->name }}</h6>
                                            </div>
                                        </a>
                                    </td>
                                    <td>{{ $item->cons_sub_category->cons_category->category_name }} - {{ $item->cons_sub_category->sub_category_name }}</td>
                                    <td>{{ $item->brand->name }}</td>
                                    <td>{{ $item->room->name }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $item->purchase_date)->isoFormat('DD MMMM Y') }}</td>
                                    <td>{{ $item->stock_amount }}</td>
                                    <td>{{ $item->taken_amount }}</td>
                                    <td>
                                        <div class="table-actions d-flex align-items-center gap-3 fs-4">
                                            <a href="{{ route('consumable-items.edit', [$unit->slug, $item->item_code]) }}" class="text-warning"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah Data">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <form action="{{ route('consumable-items.delete', [$unit->slug, $item->item_code]) }}" method="POST">
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
                <div class="tab-pane fade" id="stok-habis" role="tabpanel">
                    <div class="table-responsive">
                        <table id="dataTable2" class="table table-striped align-middle" style="width:100%">
                            <thead class="table-secondary">
                                <tr>
                                    <th>#</th>
                                    <th>NAMA BARANG</th>
                                    <th>KATEGORI</th>
                                    <th>MERK</th>
                                    <th>R. PENYIMPANAN</th>
                                    <th>TANGGAL BELI</th>
                                    <th>STOK</th>
                                    <th>DIAMBIL</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items_stock as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="productlist">
                                        <a class="d-flex align-items-center gap-2" href="{{ route('consumable-items.show', [$unit->slug, $item->item_code]) }}">
                                            <div class="product-box">
                                                <img src="{{ asset('storage/consumable-items/'. $item->image) }}" alt="{{ $item->item_code }}">
                                            </div>
                                            <div>
                                                <h6 class="mb-0 product-title">{{ $item->name }}</h6>
                                            </div>
                                        </a>
                                    </td>
                                    <td>{{ $item->cons_sub_category->cons_category->category_name }} - {{ $item->cons_sub_category->sub_category_name }}</td>
                                    <td>{{ $item->brand->name }}</td>
                                    <td>{{ $item->room->name }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $item->purchase_date)->isoFormat('DD MMMM Y') }}</td>
                                    <td>{{ $item->stock_amount }}</td>
                                    <td>{{ $item->taken_amount }}</td>
                                    <td>
                                        <div class="table-actions d-flex align-items-center gap-3 fs-4">
                                            <a href="{{ route('consumable-items.edit', [$unit->slug, $item->item_code]) }}" class="text-warning"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah Data">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <form action="{{ route('consumable-items.delete', [$unit->slug, $item->item_code]) }}" method="POST">
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

        </div>
    </div>
</x-layout>
