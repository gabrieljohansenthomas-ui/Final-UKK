<?php $page_title = 'Detail Peminjaman #'.$loan['id_peminjaman']; ?>
<div class="py-3 max-w-3xl space-y-4">
<a href="<?= base_url('/admin/loans') ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-500 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Kembali
</a>

<!-- Status Banner -->
<?php $sm=['pending'=>['bg-amber-50 border-amber-200 dark:bg-amber-900/10 dark:border-amber-800/30 text-amber-700 dark:text-amber-300','Menunggu Persetujuan'],'disetujui'=>['bg-blue-50 border-blue-200 dark:bg-blue-900/10 dark:border-blue-800/30 text-blue-700 dark:text-blue-300','Disetujui'],'ditolak'=>['bg-red-50 border-red-200 dark:bg-red-900/10 dark:border-red-800/30 text-red-700 dark:text-red-300','Ditolak'],'dikembalikan'=>['bg-green-50 border-green-200 dark:bg-green-900/10 dark:border-green-800/30 text-green-700 dark:text-green-300','Dikembalikan']];
[$scls,$slbl]=$sm[$loan['status']]??['bg-gray-50 border-gray-200 text-gray-700',ucfirst($loan['status'])]; ?>
<div class="border rounded-2xl p-4 <?= $scls ?> text-sm font-semibold flex items-center gap-2">
    <i class="fas fa-info-circle"></i> Status: <?= $slbl ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Detail Peminjaman -->
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4 text-sm sm:text-base flex items-center gap-2"><i class="fas fa-calendar text-indigo-500"></i>Informasi Peminjaman</h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <?php $rows=[['Tanggal Pinjam',date('d F Y',strtotime($loan['tanggal_pinjam']))],['Rencana Kembali',date('d F Y',strtotime($loan['tanggal_kembali_rencana']))],['Diproses Oleh',$loan['nama_pemroses']??'–'],['Tanggal Proses',$loan['tanggal_proses']?date('d F Y H:i',strtotime($loan['tanggal_proses'])):'–']];
                if($loan['tanggal_kembali_aktual'])$rows[]=['Dikembalikan',date('d F Y',strtotime($loan['tanggal_kembali_aktual']))];
                foreach($rows as [$dt,$dd]): ?>
                <div class="bg-slate-50 dark:bg-gray-700/50 rounded-xl p-3">
                    <dt class="text-xs text-gray-500 dark:text-gray-400 mb-0.5"><?= $dt ?></dt>
                    <dd class="text-sm font-semibold text-gray-800 dark:text-gray-200"><?= esc($dd) ?></dd>
                </div>
                <?php endforeach; ?>
                <?php if($loan['denda']>0): ?>
                <div class="sm:col-span-2 bg-red-50 dark:bg-red-900/20 rounded-xl p-3 border border-red-100 dark:border-red-800/30">
                    <dt class="text-xs text-red-500 mb-0.5">Denda Keterlambatan</dt>
                    <dd class="text-xl font-bold text-red-600">Rp <?= number_format($loan['denda'],0,',','.') ?></dd>
                </div>
                <?php endif; ?>
                <?php if($loan['alasan_penolakan']): ?>
                <div class="sm:col-span-2 bg-red-50 dark:bg-red-900/20 rounded-xl p-3 border border-red-100 dark:border-red-800/30">
                    <dt class="text-xs text-red-500 mb-1">Alasan Penolakan</dt>
                    <dd class="text-sm text-red-700 dark:text-red-300"><?= esc($loan['alasan_penolakan']) ?></dd>
                </div>
                <?php endif; ?>
            </dl>
        </div>
        <!-- Actions -->
        <?php if($loan['status']==='pending'): ?>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5" x-data="{showR:false}">
            <h3 class="font-bold text-gray-800 dark:text-white mb-3 text-sm">Ambil Tindakan</h3>
            <div class="flex flex-col sm:flex-row gap-3">
                <?= form_open('/admin/loans/approve/'.$loan['id_peminjaman'],['class'=>'flex-1']) ?><?= csrf_field() ?>
                <button type="submit" class="w-full h-11 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-semibold flex items-center justify-center gap-2"><i class="fas fa-check"></i>Setujui Peminjaman</button>
                <?= form_close() ?>
                <button @click="showR=!showR" class="flex-1 h-11 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold flex items-center justify-center gap-2"><i class="fas fa-times"></i>Tolak</button>
            </div>
            <div x-show="showR" x-cloak class="mt-3">
                <?= form_open('/admin/loans/reject/'.$loan['id_peminjaman']) ?><?= csrf_field() ?>
                <textarea name="alasan_penolakan" rows="3" required placeholder="Alasan penolakan..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-red-500 resize-none mb-3"></textarea>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 h-10 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold">Konfirmasi Tolak</button>
                    <button type="button" @click="showR=false" class="flex-1 h-10 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm">Batal</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
        <?php elseif($loan['status']==='disetujui'): ?>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <?= form_open('/admin/loans/return/'.$loan['id_peminjaman']) ?><?= csrf_field() ?>
            <button type="submit" onclick="return confirm('Proses pengembalian buku?')"
                    class="w-full h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold flex items-center justify-center gap-2 transition-colors">
                <i class="fas fa-undo"></i> Proses Pengembalian
            </button>
            <?= form_close() ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar: Anggota + Buku -->
    <div class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 text-center">
            <img src="<?= base_url('uploads/profiles/'.($loan['foto_profil']??'default.png')) ?>"
                 class="w-16 h-16 rounded-full object-cover mx-auto mb-3 ring-4 ring-indigo-100 dark:ring-indigo-900/30"
                 onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($loan['nama_anggota']) ?>&background=6366f1&color=fff&size=64'">
            <h4 class="font-bold text-gray-800 dark:text-white text-sm"><?= esc($loan['nama_anggota']) ?></h4>
            <p class="text-xs text-gray-500 mt-0.5"><?= esc($loan['email_anggota']) ?></p>
            <?php if($loan['nim_nis']): ?><p class="text-xs text-indigo-600 dark:text-indigo-400 font-medium mt-1">NIM: <?= esc($loan['nim_nis']) ?></p><?php endif; ?>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <img src="<?= base_url('uploads/covers/'.($loan['gambar_buku']??'no-cover.png')) ?>"
                 class="w-full rounded-xl object-cover mb-3" style="height:140px;object-fit:cover"
                 onerror="this.src='https://via.placeholder.com/240x140/e2e8f0/94a3b8?text=📖'">
            <h4 class="font-bold text-gray-800 dark:text-white text-sm leading-snug"><?= esc($loan['judul_buku']) ?></h4>
            <p class="text-xs text-gray-500 mt-0.5"><?= esc($loan['pengarang']) ?></p>
        </div>
    </div>
</div>
</div>
