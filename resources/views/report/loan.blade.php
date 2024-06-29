<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LAPORAN PINJAMAN {{ $tahun ?? '' }}</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
            font-size: 13px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <div style="margin-bottom: 20px">
        <img src="{{ asset('static/logo-for-print.png') }}" alt="logo" width="200px">
    </div>

    <div style="text-align: center; margin-top: 10px; margin-bottom: 20px">
        <h3 style="margin-bottom: 0px">LAPORAN PINJAMAN</h3>

        @if($tahun)
            <h3 style="margin-bottom: 0px; margin-top: 5px;">TAHUN {{ $tahun }}</h3>
        @endif

        @if ($status)
            <h3 style="margin-top: 5px;">STATUS {{ $status ? 'LUNAS' : 'BELUM LUNAS' }}</h3>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>NASABAH</th>
                <th>NO KTP</th>
                <th>PINJAMAN</th>
                <th>BUNGA</th>
                <th>ANGSURAN</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $value)
                <tr>
                    <td>{{ $value->nasabah->name }}</td>
                    <td>{{ $value->nasabah->number_identity }}</td>
                    <td>{{ money_format_idr($value->amount) }}</td>
                    <td>% {{ $value->interest }}</td>
                    <td>{{ $value->installments }}x per {{ $value->installments_type }}</td>
                    <td>{{ $value->detail->date_repayment ? 'Lunas' : 'Belum Lunas' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
