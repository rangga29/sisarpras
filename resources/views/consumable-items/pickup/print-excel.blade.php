<table>
    <thead>
        <tr>
            <th>No</th>
            {!! $name == '1' ? '<th>Nama Barang</th>' : '' !!}
            {!! $category == '1' ? '<th>Kategori</th>' : '' !!}
            {!! $sub_category == '1' ? '<th>Sub Kategori</th>' : '' !!}
            {!! $consumer == '1' ? '<th>Nama Pengguna</th>' : '' !!}
            {!! $unit == '1' ? '<th>Unit</th>' : '' !!}
            {!! $date == '1' ? '<th>Tanggal Pengambilan</th>' : '' !!}
            {!! $amount == '1' ? '<th>Jumlah Barang</th>' : '' !!}
            {!! $description == '1' ? '<th>Keterangan</th>' : '' !!}
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            {!! $name == '1' ? '<td>' . $item->i_name . '</td>' : '' !!}
            {!! $category == '1' ? '<td>' . $item->c_name . '</td>' : '' !!}
            {!! $sub_category == '1' ? '<td>' . $item->sc_name . '</td>' : '' !!}
            {!! $consumer == '1' ? '<td>' . $item->consumer->name . '</td>' : '' !!}
            {!! $unit == '1' ? '<td>' . $item->unit->name . '</td>' : '' !!}
            {!! $date == '1' ? '<td>' . \Carbon\Carbon::createFromFormat('Y-m-d', $item->pickup_date)->isoFormat('DD MMMM Y') . '</td>' : '' !!}
            {!! $amount == '1' ? '<td>' . ($item->amount == '0' ? '0' : $item->amount) . '</td>' : '' !!}
            {!! $description == '1' ? '<td>' . $item->description . '</td>' : '' !!}
        </tr>
        @endforeach
    </tbody>
</table>
