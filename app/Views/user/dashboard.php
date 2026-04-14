<?php $page_title = 'Dashboard';
function timeAgo(string $dt): string {
    $diff = (new DateTime())->diff(new DateTime($dt));
    if($diff->y>0) return $diff->y.' tahun lalu';
    if($diff->m>0) return $diff->m.' bulan lalu';
    if($diff->d>0) return $diff->d.' hari lalu';
    if($diff->h>0) return $diff->h.' jam lalu';
    if($diff->i>0) return $diff->i.' menit lalu';
    return 'Baru saja';
}
?>
<div class="py-3 space-y-4">

<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-emerald-500 to-teal-600 dark:from-emerald-700 dark:to-teal-800 rounded-2xl p-5 shadow-lg">
    <div class="flex items-center gap-4">
        <?php $foto = session('foto_profil') ?: 'default.png'; ?>
        <img src="<?= base_url('uploads/profiles/'.$foto) ?>"
             class="w-14 h-14 sm:w-16 sm:h-16 rounded-full border-3 border-white/40 object-cover flex-shrink-0 shadow-md"
             style="border-width:3px"
             onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode(session('nama_lengkap')) ?>&background=ffffff&color=10b981&size=64'">
        <div class="min-w-0">
            <p class="text-emerald-100 text-xs sm:text-sm">Selamat datang,</p>
            <h2 class="text-white font-bold text-lg sm:text-xl leading-tight truncate"><?= session('nama_lengkap') ?></h2>
            <p class="text-emerald-200 text-xs mt-0.5"><?= date('l, d F Y') ?></p>
        </div>
    </div>
</div>

<!-- Stats — 3 kolom -->
<div class="grid grid-cols-3 gap-3">
    <?php
    $stats=[
        ['Total Buku',$totalBuku,'fas fa-book','indigo'],
        ['Total Pinjam',$totalPinjam,'fas fa-history','blue'],
        ['Sedang Pinjam',$sedangPinjam,'fas fa-book-open','amber'],
    ];
    foreach($stats as [$l,$v,$ic,$c]):
    ?>
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-3 sm:p-4 text-center shadow-sm">
        <div class="w-9 h-9 bg-<?=$c?>-100 dark:bg-<?=$c?>-900/30 rounded-xl flex items-center justify-center mx-auto mb-2">
            <i class="<?=$ic?> text-<?=$c?>-600 dark:text-<?=$c?>-400 text-sm"></i>
        </div>
        <div class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white"><?= $v ?></div>
        <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 leading-tight mt-0.5"><?= $l ?></div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Notifications -->
<?php if(!empty($notifications)): ?>
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-700">
        <h3 class="font-semibold text-gray-700 dark:text-gray-200 text-sm flex items-center gap-2">
            <i class="fas fa-bell text-amber-500 text-sm"></i> Notifikasi Baru
            <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"><?= count($notifications) ?></span>
        </h3>
        <a href="<?= base_url('/user/notifications') ?>" class="text-emerald-500 text-xs font-medium hover:underline">Semua →</a>
    </div>
    <div class="divide-y divide-gray-50 dark:divide-gray-700">
        <?php foreach($notifications as $n): ?>
        <a href="<?= base_url('/user/notifications/read/'.$n['id_notifikasi']) ?>"
           class="flex items-start gap-3 px-4 py-3 hover:bg-emerald-50 dark:hover:bg-emerald-900/10 transition-colors">
            <div class="w-2 h-2 bg-red-500 rounded-full flex-shrink-0 mt-1.5"></div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate"><?= esc($n['judul']) ?></div>
                <div class="text-xs text-gray-500 dark:text-gray-400 truncate"><?= esc($n['pesan']) ?></div>
                <div class="text-[10px] text-gray-400 mt-0.5"><?= timeAgo($n['created_at']) ?></div>
            </div>
            <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 text-xs flex-shrink-0 mt-1"></i>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Rekomendasi -->
<div>
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2 text-sm sm:text-base">
            <i class="fas fa-star text-yellow-400 text-sm"></i> Rekomendasi Buku
        </h3>
        <a href="<?= base_url('/user/catalog') ?>" class="text-emerald-500 text-xs font-medium hover:underline">Semua →</a>
    </div>
    <!-- Horizontal scroll on mobile, grid on desktop -->
    <div class="flex gap-3 overflow-x-auto pb-2 sm:grid sm:grid-cols-3 lg:grid-cols-4 sm:overflow-visible sm:pb-0 -mx-3 sm:mx-0 px-3 sm:px-0" style="-webkit-overflow-scrolling:touch">
        <?php foreach($rekomendasi as $book):
        $available = ($book['stok']-$book['dipinjam']) > 0;
        $rat = round($book['avg_rating']);
        ?>
        <a href="<?= base_url('/user/catalog/detail/'.$book['id_buku']) ?>"
           class="flex-none w-36 sm:w-auto bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="relative overflow-hidden" style="height:130px">
                <img src="<?= base_url('uploads/covers/'.($book['gambar']??'no-cover.png')) ?>"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                     onerror="this.src='https://via.placeholder.com/144x130/e2e8f0/94a3b8?text=📖'">
                <?php if(!$available): ?>
                <div class="absolute inset-0 bg-black/55 flex items-center justify-center">
                    <span class="text-white text-[10px] font-bold bg-red-500 px-1.5 py-0.5 rounded">Habis</span>
                </div>
                <?php else: ?>
                <span class="absolute top-1.5 right-1.5 bg-green-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full"><?= $book['stok']-$book['dipinjam'] ?></span>
                <?php endif; ?>
            </div>
            <div class="p-2.5">
                <div class="text-xs font-semibold text-gray-800 dark:text-gray-200 leading-snug" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden"><?= esc($book['judul_buku']) ?></div>
                <div class="text-[10px] text-gray-500 truncate mt-0.5"><?= esc($book['pengarang']) ?></div>
                <div class="flex items-center gap-0.5 mt-1">
                    <?php for($s=1;$s<=5;$s++): ?>
                    <i class="fas fa-star text-[9px] <?= $s<=$rat?'text-yellow-400':'text-gray-200 dark:text-gray-600' ?>"></i>
                    <?php endfor; ?>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
        <?php if(empty($rekomendasi)): ?>
        <div class="col-span-4 text-center py-10 text-gray-400 text-sm">
            <i class="fas fa-book text-3xl mb-2 block opacity-20"></i>Belum ada buku tersedia
        </div>
        <?php endif; ?>
    </div>
</div>
</div>
