<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LAPORAN NASABAH {{ $tahun ?? '' }}</title>
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
        <h3 style="margin-bottom: 0px">LAPORAN NASABAH</h3>

        @if($tahun)
            <h3 style="margin-top: 5px;">TAHUN {{ $tahun }}</h3>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>NO KTP</th>
                <th>NAMA</th>
                <th>ALAMAT</th>
                <th>PEKERJAAN</th>
                <th>NO PONSEL</th>
                <th>UMUR</th>
                <th>EMAIL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $value)
                <tr>
                    <td>{{ $value->number_identity }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->address }}</td>
                    <td>{{ $value->job }}</td>
                    <td>{{ $value->phone }}</td>
                    <td>{{ $value->age }}</td>
                    <td>{{ $value->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
