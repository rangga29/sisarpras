<x-layout>
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3 fw-semibold">{{ $item->name }}</div>
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
                        <a href="{{ route('non-consumable-items', $unit->slug) }}">Data Barang</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <hr>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card border shadow-none radius-10">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="fw-semibold">Kode Barang</td>
                                            <td>
                                                {{ $item->non_cons_sub_category->non_cons_category->category_code }}.{{
                                                $item->non_cons_sub_category->sub_category_code }}.{{ $item->item_number }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Nama Barang</td>
                                            <td>{{ $item->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Kategori Barang</td>
                                            <td>{{ $item->non_cons_sub_category->non_cons_category->category_name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Sub Kategori Barang</td>
                                            <td>{{ $item->non_cons_sub_category->sub_category_name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Merk Barang</td>
                                            <td>{{ $item->brand->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Vendor Barang</td>
                                            <td>{{ $item->shop->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Sumber Dana</td>
                                            <td>{{ $item->fund->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Ruangan Penyimpanan</td>
                                            <td>{{ $item->room->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Kondisi Barang</td>
                                            <td>{{ $item->non_cons_condition->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bolder">Harga Barang</td>
                                            <td>{{ 'Rp.' . number_format($item->price, 2, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bolder">Tanggal Pembelian</td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $item->purchase_date)->isoFormat('DD MMMM Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bolder">Ketersediaan</td>
                                            <td>{{ $item->availability ? 'Tersedia' : 'Tidak Tersedia' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bolder">Kelengkapan</td>
                                            <td>{{ $item->include }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bolder">Tanda Terima Barang</td>
                                            <td><a href="{{ asset('storage/non-consumable-items/' . $item->receipt) }}" target="_BLANK">
                                                    Tanda Terima Barang
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bolder">Tanggal Tambah Data</td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->isoFormat('DD MMMM Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bolder">Keterangan</td>
                                            <td>{{ $item->description }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card border shadow-none bg-light radius-10">
                        <div class="card-body">
                            <img src="{{ asset('storage/non-consumable-items/' . $item->image) }}" alt="{{ $item->item_code }}" class="w-100">
                        </div>
                    </div>
                    <div class="card border shadow-none bg-light radius-10">
                        <div class="card-body">
                            <div class="d-grid text-nowrap gap-2">
                                <a href="{{ route('non-consumable-items.edit', [$unit->slug, $item->item_code]) }}" class="btn btn-sm btn-warning mb-2">
                                    <h6 class="mt-2">
                                        <i class="bi bi-pencil-fill"></i> UBAH DATA
                                    </h6>
                                </a>
                                <form action="{{ route('non-consumable-items.delete', [$unit->slug, $item->item_code]) }}" method="POST" class="d-inline">
                                    @method('DELETE')
                                    @csrf
                                    <div class="d-grid">
                                        <button class="btn btn-sm btn-danger mb-2" onclick="return confirm('Yakin Ingin Menghapus Data Ini?')">
                                            <h6 class="mt-2">
                                                <i class="bi bi-trash-fill"></i> HAPUS DATA
                                            </h6>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layout>
