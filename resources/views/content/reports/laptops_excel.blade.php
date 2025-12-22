<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Unit</th>
            <th>Posisi</th>
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
                <td>{{ $item->unit }}</td>
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
