<table>
    <thead>
        <tr>
            <th>No</th>
            {!! $code == '1' ? '<th>Kode Barang</th>' : '' !!}
            {!! $name == '1' ? '<th>Nama Barang</th>' : '' !!}
            {!! $category == '1' ? '<th>Nama Kategori</th>' : '' !!}
            {!! $sub_category == '1' ? '<th>Nama Sub Kategori</th>' : '' !!}
            {!! $brand == '1' ? '<th>Nama Merk</th>' : '' !!}
            {!! $shop == '1' ? '<th>Nama Vendor</th>' : '' !!}
            {!! $fund == '1' ? '<th>Nama Sumber Dana</th>' : '' !!}
            {!! $room == '1' ? '<th>Ruangan Penyimpanan</th>' : '' !!}
            {!! $condition == '1' ? '<th>Nama Kondisi</th>' : '' !!}
            {!! $unit == '1' ? '<th>Unit</th>' : '' !!}
            {!! $price == '1' ? '<th>Harga Barang</th>' : '' !!}
            {!! $purchase_date == '1' ? '<th>Tanggal Pembelian</th>' : '' !!}
            {!! $availability == '1' ? '<th>Ketersediaan</th>' : '' !!}
            {!! $include == '1' ? '<th>Kelengkapan</th>' : '' !!}
            {!! $description == '1' ? '<th>Keterangan</th>' : '' !!}
            {!! $insert_date == '1' ? '<th>Tanggal Tambah Data</th>' : '' !!}
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            {!! $code == '1' ? '<td>' . $item->c_code . '.' . $item->sc_code . '.' . $item->item_number . '</td>' : '' !!}
            {!! $name == '1' ? '<td>' . $item->name . '</td>' : '' !!}
            {!! $category == '1' ? '<td>' . $item->c_name . '</td>' : '' !!}
            {!! $sub_category == '1' ? '<td>' . $item->sc_name . '</td>' : '' !!}
            {!! $brand == '1' ? '<td>' . $item->brand->name . '</td>' : '' !!}
            {!! $shop == '1' ? '<td>' . $item->shop->name . '</td>' : '' !!}
            {!! $fund == '1' ? '<td>' . $item->fund->name . '</td>' : '' !!}
            {!! $room == '1' ? '<td>' . $item->room->name . '</td>' : '' !!}
            {!! $condition == '1' ? '<td>' . $item->non_cons_condition->name . '</td>' : '' !!}
            {!! $unit == '1' ? '<td>' . $item->unit->name . '</td>' : '' !!}
            {!! $price == '1' ? '<td>Rp.' . number_format($item->price, 2, ',', '.') . '</td>' : '' !!}
            {!! $purchase_date == '1' ? '<td>' . \Carbon\Carbon::createFromFormat('Y-m-d', $item->purchase_date)->isoFormat('DD MMMM Y') . '</td>' : ''
            !!}
            {!! $availability == '1' ? '<td>' . ($item->availability = 1 ? 'Tersedia' : 'Tidak Tersedia') . '</td>' : '' !!}
            {!! $include == '1' ? '<td>' . $item->include . '</td>' : '' !!}
            {!! $description == '1' ? '<td>' . $item->description . '</td>' : '' !!}
            {!! $insert_date == '1' ? '<td>' . \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->isoFormat('DD MMMM Y') . '</td>' : ''
            !!}
        </tr>
        @endforeach
    </tbody>
</table>
