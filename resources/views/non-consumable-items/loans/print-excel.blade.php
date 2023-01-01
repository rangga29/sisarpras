<table>
    <thead>
        <tr>
            <th>No</th>
            {!! $name == '1' ? '<th>Nama Barang</th>' : '' !!}
            {!! $category == '1' ? '<th>Nama Kategori</th>' : '' !!}
            {!! $sub_category == '1' ? '<th>Nama Sub Kategori</th>' : '' !!}
            {!! $consumer == '1' ? '<th>Nama Peminjam</th>' : '' !!}
            {!! $unit == '1' ? '<th>Unit</th>' : '' !!}
            {!! $condition_loan == '1' ? '<th>Kondisi Peminjaman</th>' : '' !!}
            {!! $condition_return == '1' ? '<th>Kondisi Pengembalian</th>' : '' !!}
            {!! $loan_date == '1' ? '<th>Tanggal Peminjaman</th>' : '' !!}
            {!! $return_date == '1' ? '<th>Tanggal Pengembalian</th>' : '' !!}
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
            {!! $condition_loan == '1' ? '<td>' . $item->loan_condition->name . '</td>' : '' !!}
            {!! $condition_return == '1' ? '<td>' . ($item->con_return_id == null ? '' : $item->loan_return_condition->name) . '</td>' : '' !!}
            {!! $loan_date == '1' ? '<td>' . \Carbon\Carbon::createFromFormat('Y-m-d', $item->loan_date)->isoFormat('DD MMMM Y') . '</td>' : '' !!}
            {!! $return_date == '1' ? '<td>' . ($item->return_date == null ? '' : \Carbon\Carbon::createFromFormat('Y-m-d',
                $item->return_date)->isoFormat('DD MMMM Y')) . '</td>' : '' !!}
            {!! $description == '1' ? '<td>' . $item->description . '</td>' : '' !!}
        </tr>
        @endforeach
    </tbody>
</table>
