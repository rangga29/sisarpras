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
                    <h5>Tanggal Peminjaman : {{ $firstDate }}</h5>
                    @else
                    <h5>Tanggal Peminjaman : {{ $firstDate }} - {{ $lastDate }}</h5>
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
    </div>
</body>

</html>
