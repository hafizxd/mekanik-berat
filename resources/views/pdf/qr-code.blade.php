<!DOCTYPE html>

@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body style="display: flex; justify-content: center; width: 100%; min-height: 100vh">
    <div>
        <h1>QR COY</h1>
        <div class="visible-print text-center">
            <img src="data:image/png;base64, {!! base64_encode(QrCode::size(300)->generate($item->id)) !!} ">
        </div>
    </div>
</body>

</html>
