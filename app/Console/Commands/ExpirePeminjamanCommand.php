<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DataPeminjam;
use App\Models\HistoriPeminjaman;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpirePeminjamanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peminjaman:expire-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengubah status peminjaman yang sudah lebih dari 3 tahun menjadi expired';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Memulai pengecekan peminjaman yang sudah > 3 tahun...');

        $tigaTahunLalu = Carbon::now()->subYears(3);

        $peminjamanExpired = DataPeminjam::where('status_peminjaman', 'active')
            ->where('tanggal_mulai', '<=', $tigaTahunLalu)
            ->get();

        if ($peminjamanExpired->isEmpty()) {
            $this->info('Tidak ada peminjaman yang perlu diexpire.');
            return Command::SUCCESS;
        }

        $count = 0;

        foreach ($peminjamanExpired as $pinjam) {
            try {
                DB::transaction(function () use ($pinjam) {
                    if ($pinjam->laptop) {
                        $pinjam->laptop->update(['status' => 'diarsip']);
                    }

                    HistoriPeminjaman::where('laptop_id', $pinjam->laptop_id)
                        ->where('user_id', $pinjam->user_id)
                        ->where('status', 'aktif')
                        ->update([
                            'status' => 'selesai',
                            'tanggal_selesai' => now(),
                        ]);

                    $pinjam->update(['status_peminjaman' => 'expired']);
                });

                $count++;
                $this->info("✓ Peminjaman ID {$pinjam->id} - {$pinjam->nama} ({$pinjam->department}) berhasil diexpire");

            } catch (\Exception $e) {
                $this->error("✗ Gagal expire peminjaman ID {$pinjam->id}: " . $e->getMessage());
            }
        }

        $this->info("Selesai! Total {$count} peminjaman berhasil diexpire.");

        return Command::SUCCESS;
    }
}