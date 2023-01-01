<table>
    <thead>
        <tr>
            <th>No</th>
            {!! $name == '1' ? '<th>Nama Barang</th>' : '' !!}
            {!! $category == '1' ? '<th>Nama Kategori</th>' : '' !!}
            {!! $sub_category == '1' ? '<th>Nama Sub Kategori</th>' : '' !!}
            {!! $room == '1' ? '<th>Nama Ruangan</th>' : '' !!}
            {!! $unit == '1' ? '<th>Unit</th>' : '' !!}
            {!! $condition_placement == '1' ? '<th>Kondisi Penempatan</th>' : '' !!}
            {!! $condition_return == '1' ? '<th>Kondisi Pengembalian</th>' : '' !!}
            {!! $placement_date == '1' ? '<th>Tanggal Penempatan</th>' : '' !!}
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
            {!! $room == '1' ? '<td>' . $item->room->name . '</td>' : '' !!}
            {!! $unit == '1' ? '<td>' . $item->unit->name . '</td>' : '' !!}
            {!! $condition_placement == '1' ? '<td>' . $item->placement_condition->name . '</td>' : '' !!}
            {!! $condition_return == '1' ? '<td>' . ($item->con_return_id == null ? '' : $item->placement_return_condition->name) . '</td>' : '' !!}
            {!! $placement_date == '1' ? '<td>' . \Carbon\Carbon::createFromFormat('Y-m-d', $item->placement_date)->isoFormat('DD MMMM Y') . '</td>' : '' !!}
            {!! $return_date == '1' ? '<td>' . ($item->return_date == null ? '' : \Carbon\Carbon::createFromFormat('Y-m-d',
                $item->return_date)->isoFormat('DD MMMM Y')) . '</td>' : '' !!}
            {!! $description == '1' ? '<td>' . $item->description . '</td>' : '' !!}
        </tr>
        @endforeach
    </tbody>
</table>
