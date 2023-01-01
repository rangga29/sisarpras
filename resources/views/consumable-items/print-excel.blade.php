<table>
    <thead>
        <tr>
            <th>No</th>
            {!! $name == '1' ? '<th>Nama Barang</th>' : '' !!}
            {!! $category == '1' ? '<th>Kategori</th>' : '' !!}
            {!! $sub_category == '1' ? '<th>Sub Kategori</th>' : '' !!}
            {!! $brand == '1' ? '<th>Merk</th>' : '' !!}
            {!! $shop == '1' ? '<th>Vendor</th>' : '' !!}
            {!! $fund == '1' ? '<th>Sumber Dana</th>' : '' !!}
            {!! $room == '1' ? '<th>Ruangan Penyimpanan</th>' : '' !!}
            {!! $unit == '1' ? '<th>Unit</th>' : '' !!}
            {!! $price == '1' ? '<th>Harga Barang</th>' : '' !!}
            {!! $purchase_date == '1' ? '<th>Tanggal Pembelian</th>' : '' !!}
            {!! $initial_amount == '1' ? '<th>Jumlah Awal</th>' : '' !!}
            {!! $taken_amount == '1' ? '<th>Jumlah Diambil</th>' : '' !!}
            {!! $stock_amount == '1' ? '<th>Jumlah Stok</th>' : '' !!}
            {!! $description == '1' ? '<th>Keterangan</th>' : '' !!}
            {!! $insert_date == '1' ? '<th>Tanggal Tambah Data</th>' : '' !!}
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            {!! $name == '1' ? '<td>' . $item->name . '</td>' : '' !!}
            {!! $category == '1' ? '<td>' . $item->cons_sub_category->cons_category->category_name . '</td>' : '' !!}
            {!! $sub_category == '1' ? '<td>' . $item->cons_sub_category->sub_category_name . '</td>' : '' !!}
            {!! $brand == '1' ? '<td>' . $item->brand->name . '</td>' : '' !!}
            {!! $shop == '1' ? '<td>' . $item->shop->name . '</td>' : '' !!}
            {!! $fund == '1' ? '<td>' . $item->fund->name . '</td>' : '' !!}
            {!! $room == '1' ? '<td>' . $item->room->name . '</td>' : '' !!}
            {!! $unit == '1' ? '<td>' . $item->unit->name . '</td>' : '' !!}
            {!! $price == '1' ? '<td>Rp.' . number_format($item->price, 2, ',', '.') . '</td>' : '' !!}
            {!! $purchase_date == '1' ? '<td>' . \Carbon\Carbon::createFromFormat('Y-m-d', $item->purchase_date)->isoFormat('DD MMMM Y') . '
            </td>' : '' !!}
            {!! $initial_amount == '1' ? '<td>' . ($item->initial_amount == '0' ? '0' : $item->initial_amount) . '</td>' : '' !!}
            {!! $taken_amount == '1' ? '<td>' . ($item->taken_amount == '0' ? '0' : $item->taken_amount) . '</td>' : '' !!}
            {!! $stock_amount == '1' ? '<td>' . ($item->stock_amount == '0' ? '0' : $item->stock_amount) . '</td>' : '' !!}
            {!! $description == '1' ? '<td>' . $item->description . '</td>' : '' !!}
            {!! $insert_date == '1' ? '<td>' . \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->isoFormat('DD MMMM Y') . '
            </td>' : '' !!}
        </tr>
        @endforeach
    </tbody>
</table>
