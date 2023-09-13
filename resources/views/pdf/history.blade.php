<!DOCTYPE html>

@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        table,
        tr,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        td {
            padding: 4px 2px;
        }
    </style>
</head>

<body>
    <h3 style="margin-bottom: 20px; text-align: center;">Riwayat Perbaikan Alat {{ $item->jenis }} {{ $item->type }}</h3>

    <table style="width: 100%;">
        <thead>
            <tr>
                <th scope="col" class="px-6 py-4">#</th>
                <th scope="col" class="px-6 py-4">User</th>
                <th scope="col" class="px-6 py-4">Tanggal</th>
                <th scope="col" class="px-6 py-4">Hours Meter</th>
                <th scope="col" class="px-6 py-4">Catatan</th>
                <th scope="col" class="px-6 py-4">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $count = 1; @endphp
            @foreach ($reparations as $key => $reparation)
                <tr>
                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $count++ }}</td>
                    <td class="whitespace-nowrap px-6 py-4">{{ isset($reparation->user) ? $reparation->user->name : '-' }}</td>
                    <td class="whitespace-nowrap px-6 py-4">{{ date('d-m-Y', strtotime($reparation->updated_at)) }}</td>
                    <td class="whitespace-nowrap px-6 py-4">{{ number_format($reparation->hours_meter, 0, ',', '.') }} jam</td>
                    <td class="whitespace-nowrap px-6 py-4">{{ $reparation->note }}</td>
                    <td class="whitespace-nowrap px-6 py-4">
                        @if ($reparation->status == 1)
                            Bekerja
                        @else
                            Perbaikan
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
