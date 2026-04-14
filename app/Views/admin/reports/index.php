<?php $page_title = 'Laporan'; ?>
<div class="py-3 space-y-4">
<div class="flex items-center justify-between">
    <h2 class="text-base sm:text-lg font-bold text-gray-800 dark:text-white">Laporan Perpustakaan</h2>
    <div class="flex gap-2">
        <a href="<?= base_url('/admin/reports/export-pdf') ?>"
           class="inline-flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-medium px-3 sm:px-4 py-2 rounded-xl transition-colors">
            <i class="fas fa-file-pdf"></i><span class="hidden sm:inline"> PDF</span>
        </a>
        <a href="<?= base_url('/admin/reports/export-excel') ?>"
           class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-xs sm:text-sm font-medium px-3 sm:px-4 py-2 rounded-xl transition-colors">
            <i class="fas fa-file-excel"></i><span class="hidden sm:inline"> Excel</span>
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
            <i class="fas fa-exchange-alt text-white text-lg"></i>
        </div>
        <div>
            <div class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white"><?= number_format($totalPinjam) ?></div>
            <div class="text-xs text-gray-500 dark:text-gray-400">Total Peminjaman</div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
            <i class="fas fa-money-bill-wave text-white text-lg"></i>
        </div>
        <div>
            <div class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">Rp <?= number_format($totalDenda,0,',','.') ?></div>
            <div class="text-xs text-gray-500 dark:text-gray-400">Total Denda Terkumpul</div>
        </div>
    </div>
</div>

<!-- Two column on desktop, stack on mobile -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <!-- Popular Books -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2 text-sm sm:text-base">
            <i class="fas fa-fire text-orange-500"></i> 10 Buku Paling Populer
        </h3>
        <div class="space-y-3">
            <?php foreach($popularBooks as $i=>$book): ?>
            <div class="flex items-center gap-3">
                <span class="w-7 h-7 flex-shrink-0 flex items-center justify-center rounded-full text-xs font-bold
                    <?= $i===0?'bg-yellow-400 text-white':($i===1?'bg-slate-300 text-gray-700':($i===2?'bg-amber-600 text-white':'bg-gray-100 dark:bg-gray-700 text-gray-500')) ?>">
                    <?= $i+1 ?>
                </span>
                <img src="<?= base_url('uploads/covers/'.($book['gambar']??'no-cover.png')) ?>"
                     class="w-8 h-10 object-cover rounded-lg flex-shrink-0"
                     onerror="this.src='https://via.placeholder.com/32x40/e2e8f0/94a3b8?text=📖'">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate"><?= esc($book['judul_buku']) ?></div>
                    <div class="text-xs text-gray-400 truncate"><?= esc($book['pengarang']) ?></div>
                </div>
                <span class="flex-shrink-0 text-sm font-bold text-indigo-600 dark:text-indigo-400"><?= $book['total'] ?>×</span>
            </div>
            <?php endforeach; ?>
            <?php if(empty($popularBooks)): ?><p class="text-center text-gray-400 py-6 text-sm">Belum ada data</p><?php endif; ?>
        </div>
    </div>

    <!-- Active Members -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2 text-sm sm:text-base">
            <i class="fas fa-trophy text-yellow-500"></i> 10 Anggota Paling Aktif
        </h3>
        <div class="space-y-3">
            <?php foreach($activeMembers as $i=>$m): ?>
            <div class="flex items-center gap-3">
                <span class="w-7 h-7 flex-shrink-0 flex items-center justify-center rounded-full text-xs font-bold
                    <?= $i===0?'bg-yellow-400 text-white':($i===1?'bg-slate-300 text-gray-700':($i===2?'bg-amber-600 text-white':'bg-gray-100 dark:bg-gray-700 text-gray-500')) ?>">
                    <?= $i+1 ?>
                </span>
                <img src="<?= base_url('uploads/profiles/'.($m['foto_profil']??'default.png')) ?>"
                     class="w-8 h-8 rounded-full object-cover border-2 border-gray-100 flex-shrink-0"
                     onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($m['nama_lengkap']) ?>&size=32&background=6366f1&color=fff'">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate"><?= esc($m['nama_lengkap']) ?></div>
                    <div class="text-xs text-gray-400 truncate"><?= esc($m['email']) ?></div>
                </div>
                <span class="flex-shrink-0 text-sm font-bold text-emerald-600 dark:text-emerald-400"><?= $m['total'] ?>×</span>
            </div>
            <?php endforeach; ?>
            <?php if(empty($activeMembers)): ?><p class="text-center text-gray-400 py-6 text-sm">Belum ada data</p><?php endif; ?>
        </div>
    </div>
</div>
</div>
