<?php $page_title = 'Riwayat Peminjaman'; ?>
<div class="py-3 space-y-4">

<div class="flex items-center justify-between">
    <div>
        <h2 class="text-base sm:text-lg font-bold text-gray-800 dark:text-white">Riwayat Peminjaman</h2>
        <p class="text-xs text-gray-500 dark:text-gray-400"><?= count($loans) ?> total</p>
    </div>
    <a href="<?= base_url('/user/catalog') ?>"
       class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-sm">
        <i class="fas fa-search text-xs"></i>
        <span class="hidden sm:inline">Cari </span>Buku
    </a>
</div>

<?php if(empty($loans)): ?>
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-14 text-center">
    <i class="fas fa-book-open text-4xl text-gray-200 dark:text-gray-700 mb-3 block"></i>
    <p class="text-gray-500 font-medium">Belum ada riwayat peminjaman</p>
    <p class="text-xs text-gray-400 mt-1">Mulai pinjam buku dari katalog</p>
    <a href="<?= base_url('/user/catalog') ?>" class="inline-block mt-4 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-colors">
        Lihat Katalog
    </a>
</div>
<?php else: ?>

<?php
$cfg=[
    'pending'     =>['bg-amber-50 dark:bg-amber-900/10 border-amber-200 dark:border-amber-800/30','bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300','fas fa-clock','Menunggu'],
    'disetujui'   =>['bg-blue-50 dark:bg-blue-900/10 border-blue-200 dark:border-blue-800/30','bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300','fas fa-check-circle','Disetujui'],
    'ditolak'     =>['bg-red-50 dark:bg-red-900/10 border-red-200 dark:border-red-800/30','bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300','fas fa-times-circle','Ditolak'],
    'dikembalikan'=>['bg-green-50 dark:bg-green-900/10 border-green-200 dark:border-green-800/30','bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300','fas fa-check-double','Dikembalikan'],
];
foreach($loans as $loan):
    [$cardCls,$badgeCls,$badgeIcon,$badgeLabel]=$cfg[$loan['status']]??['bg-white border-gray-200','bg-gray-100 text-gray-700','fas fa-question',ucfirst($loan['status'])];
    $isLate=$loan['status']==='disetujui'&&$loan['tanggal_kembali_rencana']<date('Y-m-d');
?>
<div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
    <!-- Status stripe top -->
    <?php if($isLate): ?><div class="h-1 bg-red-500"></div><?php endif; ?>

    <div class="p-4 flex gap-3">
        <img src="<?= base_url('uploads/covers/'.($loan['gambar_buku']??'no-cover.png')) ?>"
             class="w-14 h-20 object-cover rounded-xl flex-shrink-0 shadow-sm"
             style="object-fit:cover"
             onerror="this.src='https://via.placeholder.com/56x80/e2e8f0/94a3b8?text=📖'">

        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <h3 class="font-bold text-sm text-gray-800 dark:text-gray-200 leading-snug" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden"><?= esc($loan['judul_buku']) ?></h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate"><?= esc($loan['pengarang']) ?></p>
                </div>
                <span class="flex-shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold <?= $badgeCls ?>">
                    <i class="<?= $badgeIcon ?> text-[9px]"></i><?= $badgeLabel ?>
                </span>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-2 gap-2 mt-3">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-2">
                    <div class="text-[10px] text-gray-400">Tanggal Pinjam</div>
                    <div class="text-xs font-semibold text-gray-700 dark:text-gray-300 mt-0.5"><?= date('d M Y',strtotime($loan['tanggal_pinjam'])) ?></div>
                </div>
                <div class="rounded-lg p-2 <?= $isLate?'bg-red-50 dark:bg-red-900/20':'bg-gray-50 dark:bg-gray-700/50' ?>">
                    <div class="text-[10px] <?= $isLate?'text-red-500':'text-gray-400' ?>">Rencana Kembali</div>
                    <div class="text-xs font-semibold mt-0.5 <?= $isLate?'text-red-600':'text-gray-700 dark:text-gray-300' ?>">
                        <?= date('d M Y',strtotime($loan['tanggal_kembali_rencana'])) ?>
                        <?php if($isLate): ?><span class="ml-1">⚠️</span><?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Extra info -->
            <?php if($loan['tanggal_kembali_aktual'] || $loan['denda']>0 || $loan['alasan_penolakan']): ?>
            <div class="mt-2 space-y-1.5">
                <?php if($loan['tanggal_kembali_aktual']): ?>
                <div class="flex items-center gap-1.5 text-xs text-green-600 dark:text-green-400">
                    <i class="fas fa-check-circle text-[10px]"></i>
                    Dikembalikan: <?= date('d M Y',strtotime($loan['tanggal_kembali_aktual'])) ?>
                </div>
                <?php endif; ?>
                <?php if($loan['denda']>0): ?>
                <div class="flex items-center gap-1.5 text-xs font-bold text-red-600 dark:text-red-400">
                    <i class="fas fa-money-bill-wave text-[10px]"></i>
                    Denda: Rp <?= number_format($loan['denda'],0,',','.') ?>
                </div>
                <?php endif; ?>
                <?php if($loan['alasan_penolakan']): ?>
                <div class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-xs p-2 rounded-lg leading-snug">
                    <i class="fas fa-exclamation-circle mr-1"></i><strong>Alasan:</strong> <?= esc($loan['alasan_penolakan']) ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
