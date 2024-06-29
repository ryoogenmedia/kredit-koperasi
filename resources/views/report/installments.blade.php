<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LAPORAN PINJAMAN {{ $tahun ?? '' }} {{ $nasabah->name ?? '' }}</title>
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

        @if ($nasabah)
            <h3 style="margin-top: 5px;">{{ strtoupper($nasabah->name) }}</h3>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>NASABAH</th>
                <th>NO KTP</th>
                <th>TANGGAL ANGSURAN</th>
                <th>SISA ANGSURAN</th>
                <th>SISA PINJAMAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $value)
                <tr>
                    <td>{{ $value->nasabah->name ?? '-' }}</td>
                    <td>{{ $value->nasabah->number_identity ?? '-'}}</td>
                    <td>{{ $value->date_installments }}</td>
                    <td>{{ $value->remaining_installments }}</td>
                    <td>{{ money_format_idr($value->remaining_loan) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
