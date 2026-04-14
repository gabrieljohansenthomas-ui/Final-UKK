<?php $page_title = 'Dashboard'; ?>
<div class="py-3 space-y-4">

<!-- Stat Cards — 2 kolom mobile, 5 kolom desktop -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
<?php
$stats=[
    ['Total Buku',$totalBuku,'fas fa-book','bg-indigo-500','from-indigo-500 to-indigo-600'],
    ['Anggota',$totalAnggota,'fas fa-users','bg-emerald-500','from-emerald-500 to-emerald-600'],
    ['Total Pinjam',$totalPinjam,'fas fa-exchange-alt','bg-blue-500','from-blue-500 to-blue-600'],
    ['Menunggu',$pending,'fas fa-clock','bg-amber-500','from-amber-500 to-amber-600'],
    ['Terlambat',$terlambat,'fas fa-exclamation-triangle','bg-red-500','from-red-500 to-red-600'],
];
foreach($stats as [$lbl,$val,$icon,$bg,$grad]):
?>
<div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-3 hover:-translate-y-0.5 transition-transform">
    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br <?= $grad ?> rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
        <i class="<?= $icon ?> text-white text-base sm:text-lg"></i>
    </div>
    <div class="min-w-0">
        <div class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white leading-tight"><?= number_format($val) ?></div>
        <div class="text-xs text-gray-500 dark:text-gray-400 truncate"><?= $lbl ?></div>
    </div>
</div>
<?php endforeach; ?>
</div>

<!-- Chart + Popular — stack on mobile, side-by-side on lg -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    <!-- Chart -->
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 sm:p-5">
        <h3 class="font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2 mb-4 text-sm sm:text-base">
            <i class="fas fa-chart-line text-indigo-500"></i> Peminjaman 12 Bulan Terakhir
        </h3>
        <div class="relative" style="height:180px">
            <canvas id="loanChart"></canvas>
        </div>
    </div>

    <!-- Popular Books -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 sm:p-5">
        <h3 class="font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2 mb-4 text-sm sm:text-base">
            <i class="fas fa-fire text-orange-500"></i> Buku Populer
        </h3>
        <div class="space-y-3">
            <?php foreach($popularBooks as $i=>$book): ?>
            <div class="flex items-center gap-3">
                <span class="w-6 h-6 flex-shrink-0 flex items-center justify-center rounded-full text-xs font-bold
                    <?= $i===0?'bg-yellow-400 text-white':($i===1?'bg-slate-300 text-gray-700':($i===2?'bg-amber-600 text-white':'bg-gray-100 dark:bg-gray-700 text-gray-500')) ?>">
                    <?= $i+1 ?>
                </span>
                <img src="<?= base_url('uploads/covers/'.($book['gambar']??'no-cover.png')) ?>"
                     class="w-8 h-10 object-cover rounded-lg flex-shrink-0"
                     onerror="this.src='https://via.placeholder.com/32x40/e2e8f0/94a3b8?text=📖'">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-800 dark:text-white truncate"><?= esc($book['judul_buku']) ?></div>
                    <div class="text-xs text-gray-400"><?= $book['total'] ?>× dipinjam</div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if(empty($popularBooks)): ?>
            <p class="text-sm text-gray-400 text-center py-6">Belum ada data</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent Loans Table -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 sm:p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2 text-sm sm:text-base">
            <i class="fas fa-history text-blue-500"></i> Peminjaman Terbaru
        </h3>
        <a href="<?= base_url('/admin/loans') ?>" class="text-indigo-500 hover:text-indigo-600 text-xs font-medium flex items-center gap-1">
            Semua <i class="fas fa-arrow-right text-[10px]"></i>
        </a>
    </div>
    <!-- Mobile: Card layout | Desktop: Table -->
    <div class="sm:hidden space-y-3">
        <?php foreach($recentLoans as $loan):
        $sm=['pending'=>'bg-amber-100 text-amber-700','disetujui'=>'bg-blue-100 text-blue-700','ditolak'=>'bg-red-100 text-red-700','dikembalikan'=>'bg-green-100 text-green-700'];
        $cls=$sm[$loan['status']]??'bg-gray-100 text-gray-700'; ?>
        <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-gray-700/50 rounded-xl">
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate"><?= esc($loan['nama_lengkap']) ?></div>
                <div class="text-xs text-gray-500 dark:text-gray-400 truncate"><?= esc($loan['judul_buku']) ?></div>
                <div class="text-xs text-gray-400 mt-0.5"><?= date('d/m/Y',strtotime($loan['tanggal_pinjam'])) ?></div>
            </div>
            <div class="flex flex-col items-end gap-1.5">
                <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $cls ?>"><?= ucfirst($loan['status']) ?></span>
                <a href="<?= base_url('/admin/loans/detail/'.$loan['id_peminjaman']) ?>" class="text-indigo-500 text-xs font-medium">Detail →</a>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if(empty($recentLoans)): ?><p class="text-center text-gray-400 text-sm py-6">Belum ada peminjaman</p><?php endif; ?>
    </div>
    <!-- Desktop table -->
    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full text-sm min-w-[480px]">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-700 text-xs text-gray-400 uppercase">
                    <th class="pb-2 text-left pl-1">Anggota</th>
                    <th class="pb-2 text-left">Buku</th>
                    <th class="pb-2 text-left">Tanggal</th>
                    <th class="pb-2 text-left">Status</th>
                    <th class="pb-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
            <?php foreach($recentLoans as $loan):
            $sm=['pending'=>['bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300','Pending'],'disetujui'=>['bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300','Disetujui'],'ditolak'=>['bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300','Ditolak'],'dikembalikan'=>['bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300','Dikembalikan']];
            [$cls,$lbl]=$sm[$loan['status']]??['bg-gray-100 text-gray-700',$loan['status']]; ?>
            <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/30">
                <td class="py-2.5 pl-1 font-medium text-gray-800 dark:text-gray-200"><?= esc($loan['nama_lengkap']) ?></td>
                <td class="py-2.5 text-gray-500 dark:text-gray-400 max-w-[160px] truncate"><?= esc($loan['judul_buku']) ?></td>
                <td class="py-2.5 text-gray-500"><?= date('d/m/Y',strtotime($loan['tanggal_pinjam'])) ?></td>
                <td class="py-2.5"><span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $cls ?>"><?= $lbl ?></span></td>
                <td class="py-2.5"><a href="<?= base_url('/admin/loans/detail/'.$loan['id_peminjaman']) ?>" class="text-indigo-500 hover:underline text-xs font-medium">Detail</a></td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($recentLoans)): ?>
            <tr><td colspan="5" class="py-8 text-center text-gray-400">Belum ada peminjaman</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<script>
(function(){
    const ctx=document.getElementById('loanChart');
    const labels=<?= json_encode(array_column($monthlyData,'label')) ?>;
    const data=<?= json_encode(array_column($monthlyData,'count')) ?>;
    new Chart(ctx,{
        type:'line',
        data:{labels,datasets:[{label:'Peminjaman',data,borderColor:'#6366f1',backgroundColor:'rgba(99,102,241,.08)',borderWidth:2,tension:.4,fill:true,pointBackgroundColor:'#6366f1',pointRadius:3,pointHoverRadius:5}]},
        options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{stepSize:1,font:{size:10}},grid:{color:'rgba(0,0,0,.04)'}},x:{ticks:{font:{size:9},maxRotation:45},grid:{display:false}}}}
    });
})();
</script>
