<!DOCTYPE html>

@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body style="text-align: center; width: 50%; margin: auto;">
    <div>
        <h1>QR CODE</h1>
        <table style="margin-bottom: 50px;">
            <tr>
                <td>Jenis</td>
                <td>:</td>
                <td>{{ $item->jenis }}</td>
            </tr>
            <tr>
                <td>Type</td>
                <td>:</td>
                <td>{{ $item->type }}</td>
            </tr>
        </table>
        <div class="visible-print: text-center; margin-bottom: 100px;">
            <img src="data:image/png;base64, {!! base64_encode(QrCode::size(350)->generate($item->unique_code)) !!} ">
        </div>

        <h3>Kode Alat : {{ $item->unique_code }}</h3>
    </div>
</body>

</html>
