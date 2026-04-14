<?php $page_title = 'Peminjaman'; ?>
<div class="py-3 space-y-4">
<div class="flex items-center justify-between">
    <div>
        <h2 class="text-base sm:text-lg font-bold text-gray-800 dark:text-white">Daftar Peminjaman</h2>
        <p class="text-xs text-gray-500 dark:text-gray-400"><?= count($loans) ?> data</p>
    </div>
</div>

<!-- Filter -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
    <?= form_open('/admin/loans', ['method'=>'get', 'class'=>'flex flex-col sm:flex-row gap-3']) ?>
    <div class="flex-1 relative">
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none"><i class="fas fa-search text-sm"></i></span>
        <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Anggota atau judul buku..."
               class="w-full h-10 pl-9 pr-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder:text-gray-400">
    </div>
    <select name="status" class="h-10 px-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">Semua Status</option>
        <?php foreach(['pending'=>'Pending','disetujui'=>'Disetujui','ditolak'=>'Ditolak','dikembalikan'=>'Dikembalikan'] as $v=>$l): ?>
        <option value="<?= $v ?>" <?= $status==$v?'selected':'' ?>><?= $l ?></option>
        <?php endforeach; ?>
    </select>
    <div class="flex gap-2">
        <button type="submit" class="flex-1 sm:flex-none h-10 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors"><i class="fas fa-filter"></i></button>
        <a href="<?= base_url('/admin/loans') ?>" class="flex-1 sm:flex-none h-10 px-4 flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm rounded-xl transition-colors">Reset</a>
    </div>
    <?= form_close() ?>
</div>

<!-- Mobile Cards -->
<?php
$sm=['pending'=>['bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300','fas fa-clock'],'disetujui'=>['bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300','fas fa-check-circle'],'ditolak'=>['bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300','fas fa-times-circle'],'dikembalikan'=>['bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300','fas fa-check-double']];
?>
<?php if(empty($loans)): ?>
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-12 text-center">
    <i class="fas fa-exchange-alt text-4xl text-gray-200 dark:text-gray-700 mb-3 block"></i>
    <p class="text-gray-400 font-medium">Tidak ada data peminjaman</p>
</div>
<?php else: ?>

<!-- Mobile -->
<div class="space-y-3 sm:hidden">
    <?php foreach($loans as $loan):
    $isLate=$loan['status']==='disetujui'&&$loan['tanggal_kembali_rencana']<date('Y-m-d');
    [$badgeCls,$badgeIcon]=$sm[$loan['status']]??['bg-gray-100 text-gray-700','fas fa-question'];
    ?>
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 space-y-3" x-data="{showR:false,showD:false}">
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
                <div class="font-semibold text-sm text-gray-800 dark:text-gray-200"><?= esc($loan['nama_anggota']) ?></div>
                <div class="text-xs text-gray-500 truncate"><?= esc($loan['judul_buku']) ?></div>
            </div>
            <span class="flex-shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium <?= $badgeCls ?>">
                <i class="<?= $badgeIcon ?> text-[10px]"></i><?= ucfirst($loan['status']) ?>
            </span>
        </div>
        <div class="grid grid-cols-2 gap-2 text-xs">
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-2">
                <div class="text-gray-400">Pinjam</div>
                <div class="font-medium text-gray-700 dark:text-gray-300"><?= date('d/m/Y',strtotime($loan['tanggal_pinjam'])) ?></div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-2 <?= $isLate?'!bg-red-50 dark:!bg-red-900/10':'' ?>">
                <div class="text-gray-400">Kembali</div>
                <div class="font-medium <?= $isLate?'text-red-600':'text-gray-700 dark:text-gray-300' ?>"><?= date('d/m/Y',strtotime($loan['tanggal_kembali_rencana'])) ?><?= $isLate?' ⚠️':'' ?></div>
            </div>
        </div>
        <?php if($loan['denda']>0): ?>
        <div class="text-xs font-bold text-red-600">Denda: Rp <?= number_format($loan['denda'],0,',','.') ?></div>
        <?php endif; ?>
        <div class="flex flex-wrap gap-2 pt-1 border-t border-gray-100 dark:border-gray-700">
            <a href="<?= base_url('/admin/loans/detail/'.$loan['id_peminjaman']) ?>" class="flex-1 h-8 flex items-center justify-center gap-1.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg text-xs font-medium"><i class="fas fa-eye text-[10px]"></i> Detail</a>
            <?php if($loan['status']==='pending'): ?>
            <?= form_open('/admin/loans/approve/'.$loan['id_peminjaman'],['class'=>'flex-1']) ?><?= csrf_field() ?>
            <button type="submit" class="w-full h-8 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5"><i class="fas fa-check text-[10px]"></i> Setujui</button>
            <?= form_close() ?>
            <button @click="showR=true" class="flex-1 h-8 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5"><i class="fas fa-times text-[10px]"></i> Tolak</button>
            <?php elseif($loan['status']==='disetujui'): ?>
            <?= form_open('/admin/loans/return/'.$loan['id_peminjaman'],['class'=>'flex-1']) ?><?= csrf_field() ?>
            <button type="submit" onclick="return confirm('Konfirmasi pengembalian?')" class="w-full h-8 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5"><i class="fas fa-undo text-[10px]"></i> Kembalikan</button>
            <?= form_close() ?>
            <?php endif; ?>
        </div>
        <!-- Reject Modal -->
        <div x-show="showR" x-cloak class="fixed inset-0 bg-black/50 flex items-end sm:items-center justify-center z-50 p-4" @click.self="showR=false">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 w-full max-w-sm shadow-2xl">
                <h3 class="font-bold text-gray-800 dark:text-white mb-1 text-base">Tolak Peminjaman</h3>
                <p class="text-sm text-gray-500 mb-3">Isi alasan penolakan yang jelas.</p>
                <?= form_open('/admin/loans/reject/'.$loan['id_peminjaman']) ?><?= csrf_field() ?>
                <textarea name="alasan_penolakan" rows="3" required placeholder="Alasan penolakan..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500 resize-none mb-3"></textarea>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 h-11 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-medium">Tolak</button>
                    <button type="button" @click="showR=false" class="flex-1 h-11 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-medium">Batal</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Desktop Table -->
<div class="hidden sm:block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[700px]">
            <thead class="bg-slate-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <tr class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                    <th class="px-4 py-3 text-left">Anggota</th>
                    <th class="px-4 py-3 text-left">Buku</th>
                    <th class="px-4 py-3 text-left">Tgl Pinjam</th>
                    <th class="px-4 py-3 text-left">Rencana</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-right">Denda</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <?php foreach($loans as $loan):
            $isLate=$loan['status']==='disetujui'&&$loan['tanggal_kembali_rencana']<date('Y-m-d');
            [$badgeCls]=$sm[$loan['status']]??['bg-gray-100 text-gray-700'];
            ?>
            <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/30 <?= $isLate?'bg-red-50/30':'' ?>" x-data="{showR:false}">
                <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-200"><?= esc($loan['nama_anggota']) ?></td>
                <td class="px-4 py-3 text-gray-500 dark:text-gray-400 max-w-[150px] truncate"><?= esc($loan['judul_buku']) ?></td>
                <td class="px-4 py-3 text-gray-500 whitespace-nowrap"><?= date('d/m/Y',strtotime($loan['tanggal_pinjam'])) ?></td>
                <td class="px-4 py-3 whitespace-nowrap <?= $isLate?'text-red-600 font-semibold':'text-gray-500' ?>"><?= date('d/m/Y',strtotime($loan['tanggal_kembali_rencana'])) ?><?= $isLate?' ⚠':'' ?></td>
                <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $badgeCls ?>"><?= ucfirst($loan['status']) ?></span></td>
                <td class="px-4 py-3 text-right text-sm <?= $loan['denda']>0?'text-red-600 font-semibold':'text-gray-400' ?>"><?= $loan['denda']>0?'Rp'.number_format($loan['denda'],0,',','.'):'–' ?></td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-center gap-1">
                        <a href="<?= base_url('/admin/loans/detail/'.$loan['id_peminjaman']) ?>" class="p-1.5 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg" title="Detail"><i class="fas fa-eye text-xs"></i></a>
                        <?php if($loan['status']==='pending'): ?>
                        <?= form_open('/admin/loans/approve/'.$loan['id_peminjaman'],['class'=>'inline']) ?><?= csrf_field() ?>
                        <button type="submit" class="p-1.5 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-lg" title="Setujui"><i class="fas fa-check text-xs"></i></button>
                        <?= form_close() ?>
                        <button @click="showR=true" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg" title="Tolak"><i class="fas fa-times text-xs"></i></button>
                        <?php elseif($loan['status']==='disetujui'): ?>
                        <?= form_open('/admin/loans/return/'.$loan['id_peminjaman'],['class'=>'inline']) ?><?= csrf_field() ?>
                        <button type="submit" onclick="return confirm('Konfirmasi pengembalian buku?')" class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg" title="Kembalikan"><i class="fas fa-undo text-xs"></i></button>
                        <?= form_close() ?>
                        <?php endif; ?>
                    </div>
                    <!-- Reject modal desktop -->
                    <div x-show="showR" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" @click.self="showR=false">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-md w-full shadow-2xl">
                            <h3 class="font-bold text-gray-800 dark:text-white mb-3">Tolak Peminjaman</h3>
                            <?= form_open('/admin/loans/reject/'.$loan['id_peminjaman']) ?><?= csrf_field() ?>
                            <textarea name="alasan_penolakan" rows="3" required placeholder="Alasan penolakan..."
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500 resize-none mb-4"></textarea>
                            <div class="flex gap-3">
                                <button type="submit" class="flex-1 h-11 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold">Tolak</button>
                                <button type="button" @click="showR=false" class="flex-1 h-11 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm">Batal</button>
                            </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
</div>
