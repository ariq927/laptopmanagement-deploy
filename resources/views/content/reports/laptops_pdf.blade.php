<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman Laptop</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Peminjaman Laptop</h2>
    <table border="1" cellspacing="0" cellpadding="5" width="100%">
    <thead>
        <tr style="background-color: #f2f2f2; text-align: center;">
            <th>No</th>
            <th>Nama</th>
            <th>Department</th>
            <th>Merek Laptop</th>
            <th>Tipe Laptop</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Durasi (hari)</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($laptops as $index => $item)
            <tr>
                <td style="text-align:center;">{{ $index + 1 }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->department }}</td>
                <td>{{ $item->laptop->merek ?? '-' }}</td>
                <td>{{ $item->laptop->tipe ?? '-' }}</td>
                <td>{{ $item->tanggal_mulai }}</td>
                <td>{{ $item->tanggal_selesai }}</td>
                <td style="text-align:center;">{{ $item->durasi_peminjaman }}</td>
                <td>{{ ucfirst($item->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
