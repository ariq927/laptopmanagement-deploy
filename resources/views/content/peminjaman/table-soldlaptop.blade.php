@if($soldLaptops->count() > 0)
<div class="table-container">
  <table class="sold-table">
    <thead>
      <tr>
        <th style="width: 5%;">No</th>
        <th style="width: 25%;">Laptop</th>
        <th style="width: 25%;">Pembeli</th>
        <th style="width: 15%;">Harga Jual</th>
        <th style="width: 15%;">Tanggal Jual</th>
        <th style="width: 15%;">Catatan</th>
      </tr>
    </thead>
    <tbody>
      @foreach($soldLaptops as $index => $laptop)
      <tr class="clickable-row" 
          data-id="{{ $laptop->id }}" 
          data-url="{{ route('laptop.sold.detail', $laptop->id) }}" 
          style="cursor: pointer;">
        
        <!-- Nomor -->
        <td style="text-align: center; font-weight: 600; color: #64748b;">
          {{ $soldLaptops->firstItem() + $index }}
        </td>

        <!-- Laptop Info -->
        <td>
          <div style="display: flex; flex-direction: column; gap: 6px;">
            <div class="laptop-badge">
              <i class="bx bx-laptop"></i>
              <span>{{ $laptop->kode }}</span>
            </div>
            <div style="font-weight: 600; font-size: 0.95rem;">
              {{ $laptop->merek }} {{ $laptop->tipe }}
            </div>
            @if($laptop->spesifikasi)
            <div style="color: #64748b; font-size: 0.8rem;">
              {{ Str::limit($laptop->spesifikasi, 40) }}
            </div>
            @endif
          </div>
        </td>

        <!-- Pembeli Info -->
        <td>
          <div class="buyer-info">
            <span class="buyer-name">
              <i class="bx bx-user" style="font-size: 16px; color: #14a2ba;"></i>
              {{ $laptop->buyer_name ?? '-' }}
            </span>
            @if($laptop->buyer_id)
            <span class="buyer-code">
              <i class="bx bx-id-card" style="font-size: 14px;"></i>
              {{ $laptop->buyer_id }}
            </span>
            @endif
            @if($laptop->buyer_position)
            <span class="buyer-code">
              <i class="bx bx-briefcase" style="font-size: 14px;"></i>
              {{ $laptop->buyer_position }}
            </span>
            @endif
          </div>
        </td>

        <!-- Harga Jual -->
        <td>
          <div class="price-display">
            Rp {{ number_format($laptop->sold_price ?? 0, 0, ',', '.') }}
          </div>
        </td>

        <!-- Tanggal Jual -->
        <td>
          <div class="date-display">
            <i class="bx bx-calendar" style="font-size: 14px;"></i>
            {{ $laptop->sold_at ? \Carbon\Carbon::parse($laptop->sold_at)->format('d M Y') : '-' }}
          </div>
          @if($laptop->sold_at)
          <div style="color: #94a3b8; font-size: 0.75rem; margin-top: 2px;">
            {{ \Carbon\Carbon::parse($laptop->sold_at)->format('H:i') }} WIB
          </div>
          @endif
        </td>

        <!-- Catatan -->
        <td>
          @if($laptop->notes)
          <div style="color: #475569; font-size: 0.85rem;">
            {{ Str::limit($laptop->notes, 50) }}
          </div>
          @else
          <span style="color: #cbd5e1; font-style: italic; font-size: 0.85rem;">
            Tidak ada catatan
          </span>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<!-- Pagination -->
<div class="pagination-wrapper">
  <div class="pagination-info">
    Menampilkan <strong>{{ $soldLaptops->firstItem() }}</strong> 
    hingga <strong>{{ $soldLaptops->lastItem() }}</strong> 
    dari <strong>{{ $soldLaptops->total() }}</strong> laptop terjual
  </div>
  
  <div>
    {{ $soldLaptops->links() }}
  </div>
</div>

@else
<!-- Empty State -->
<div class="empty-state">
  <i class="bx bx-package"></i>
  <h5>Belum Ada Laptop Terjual</h5>
  <p>Data laptop yang sudah terjual akan ditampilkan di sini</p>
</div>
@endif

<style>
.clickable-row {
  transition: background-color 0.2s ease, transform 0.1s ease;
}

.clickable-row:hover {
  background-color: rgba(20, 162, 186, 0.08) !important;
  transform: translateX(2px);
}

.clickable-row:active {
  transform: translateX(0);
}
</style>
