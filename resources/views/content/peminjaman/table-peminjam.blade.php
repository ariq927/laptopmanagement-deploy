<div id="tableContent">
  <div class="table-responsive">
    <table class="table table-bordered mb-0">
      <thead style="background-color:rgba(20,162,186,0.5);">
        <tr>
          <th class="text-white fw-bold">No</th>
          <th class="text-white fw-bold">Nama</th>
          <th class="text-white fw-bold">Jabatan</th>
          <th class="text-white fw-bold">Laptop</th>
          <th class="text-white fw-bold">Tanggal Pinjam</th>
          <th class="text-white fw-bold">Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($peminjams as $i => $p)
        <tr data-id="{{ $p->id }}" style="cursor:pointer; background-color:rgba(20,162,186,0.1); transition:all 0.3s ease;"
            onmouseover="this.style.backgroundColor='rgba(20,162,186,0.25)'"
            onmouseout="this.style.backgroundColor='rgba(20,162,186,0.1)'">
          <td class="text-white fw-bold">{{ $peminjams->firstItem() + $i }}</td>
          <td class="text-white fw-bold">{{ $p->nama }}</td>
          <td class="text-white fw-bold">{{ $p->department ?? '-' }}</td>
          <td class="text-white fw-bold">{{ $p->laptop ? $p->laptop->merek.' '.$p->laptop->tipe : '-' }}</td>
          <td class="text-white fw-bold">{{ $p->tanggal_mulai }}</td>
          <td style="position:relative;">
            @if($p->status_peminjaman === 'expired')
              <button type="button" class="btn-expired no-nav" disabled>Expired</button>
            @else
              <button type="button" class="btn-table no-nav" onclick="event.stopPropagation(); toggleDropdown(this, '{{ $p->id }}')">In Use</button>
              
              <!-- dropdown -->
              <div class="status-dropdown" data-owner="{{ $p->id }}">
                <button type="button" class="no-nav" onclick="event.stopPropagation(); updateStatus('{{ $p->id }}', 'in stock')">In Stock</button>
                <button type="button" class="no-nav" onclick="event.stopPropagation(); openArchiveModal('{{ $p->id }}')">Arsip</button>
              </div>
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-white fw-bold">Belum ada data peminjaman</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3">
    <div style="color:#fff; font-weight:bold;">
      Menampilkan {{ $peminjams->firstItem() ?? 0 }} - {{ $peminjams->lastItem() ?? 0 }} dari {{ $peminjams->total() }}
    </div>
    <div>
      {{ $peminjams->appends(['search'=>$search ?? '', 'per_page'=>$perPage ?? 10, 'status_filter'=>request('status_filter')])->links() }}
    </div>
  </div>
</div>