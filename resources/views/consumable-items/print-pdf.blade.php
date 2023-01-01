<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
</head>

<body>
    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        .header {
            border-bottom: 3px solid black;
        }

        .header center {
            margin-bottom: 14px;
        }

        .header center h1 {
            font-size: 12pt;
            margin-top: 0;
            margin-bottom: 0;
        }

        .header center h5 {
            font-size: 22pt;
            font-weight: bolder;
            text-transform: uppercase;
            margin-top: 0;
            margin-bottom: 0;
        }

        .header center h2 {
            font-size: 15pt;
            font-weight: bolder;
            text-transform: uppercase;
            margin-top: 0;
            margin-bottom: 0;
        }

        .table-top table {
            width: 100%;
            font-size: 13pt;
        }

        .table-top h5 {
            margin-top: 16px;
            margin-bottom: 6px;
        }

        .table-bottom table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-bottom thead {
            background-color: #333;
            color: white;
            font-size: 10pt;
            text-transform: uppercase;
            letter-spacing: 2%;
        }

        .table-bottom tbody {
            font-size: 10pt;
        }

        .table-bottom table,
        .table-bottom th,
        .table-bottom td {
            border: 2px solid black;
        }

        .table-bottom th,
        .table-bottom td {
            padding: 10px;
        }

        .no {
            text-align: center;
        }
    </style>

    <div class="header">
        <center>
            @if($unitData->slug == 'yayasan')
            <h1>SISTEM INFORMASI SARANA PRASARANA YAYASAN PRASAMA BHAKTI</h1>
            @elseif($unitData->slug == 'tbtk')
            <h1>SISTEM INFORMASI SARANA PRASARANA TB-TK SANTA URSULA</h1>
            @elseif($unitData->slug == 'sd')
            <h1>SISTEM INFORMASI SARANA PRASARANA SD SANTA URSULA</h1>
            @else
            <h1>SISTEM INFORMASI SARANA PRASARANA SMP SANTA URSULA</h1>
            @endif
            <h5>{{ $title }}</h5>
            <h2>
                @if ($subTitle1 != '')
                {{ $subTitle1 }}
                @endif
                @if ($subTitle1 != '' && $subTitle2 != '')
                |
                @endif
                @if ($subTitle2 != '')
                {{ $subTitle2 }}
                @endif
                @if (($subTitle1 != '' && $filter != '') || ($subTitle2 != '' && $filter != ''))
                |
                @endif
                @if ($filter != '')
                {{ $filter }}
                @endif
            </h2>
        </center>
    </div>

    <div class="table-top">
        <table>
            <tr>
                <td align="left" style="width: 40%;">
                    <h5>Tanggal Unduh : {{ $todayDate }}</h5>
                </td>
                <td align="right" style="width: 60%;">
                    @if ($firstDate == $lastDate)
                    <h5>Tanggal Pembelian : {{ $firstDate }}</h5>
                    @else
                    <h5>Tanggal Pembelian : {{ $firstDate }} - {{ $lastDate }}</h5>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="table-bottom">
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
    </div>
</body>

</html>
