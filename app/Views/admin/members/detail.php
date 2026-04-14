<?php $page_title='Detail Anggota'; ?>
<div class="py-3 max-w-4xl space-y-4">
<a href="<?= base_url('/admin/members') ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-500 transition-colors"><i class="fas fa-arrow-left text-xs"></i> Kembali</a>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div class="text-center mb-4">
            <img src="<?= base_url('uploads/profiles/'.($member['foto_profil']??'default.png')) ?>"
                 class="w-20 h-20 rounded-full object-cover mx-auto mb-3 ring-4 ring-indigo-100 dark:ring-indigo-900/30"
                 onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($member['nama_lengkap']) ?>&background=6366f1&color=fff&size=80'">
            <h3 class="font-bold text-gray-800 dark:text-white"><?= esc($member['nama_lengkap']) ?></h3>
            <p class="text-xs text-gray-500 mt-0.5">@<?= esc($member['username']) ?></p>
            <span class="inline-block mt-2 px-3 py-0.5 rounded-full text-xs font-medium <?= $member['status']?'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300':'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' ?>"><?= $member['status']?'Aktif':'Nonaktif' ?></span>
        </div>
        <div class="space-y-2 text-sm">
            <?php $rows=[['fa-envelope',$member['email']],['fa-id-card',$member['nim_nis']??null],['fa-phone',$member['no_telp']??null],['fa-map-marker-alt',$member['alamat']??null],['fa-calendar','Bergabung '.date('d M Y',strtotime($member['tgl_daftar']))]];
            foreach($rows as [$ic,$val]): if(!$val)continue; ?>
            <div class="flex items-start gap-2 text-gray-600 dark:text-gray-400">
                <i class="fas <?= $ic ?> w-4 text-center text-gray-400 mt-0.5 text-sm flex-shrink-0"></i>
                <span class="text-sm leading-snug"><?= esc($val) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="font-bold text-gray-800 dark:text-white mb-4 text-sm sm:text-base">Riwayat Peminjaman (<?= count($loans) ?>)</h3>
        <?php if(empty($loans)): ?><p class="text-center text-gray-400 py-8">Belum ada riwayat</p>
        <?php else: ?>
        <div class="space-y-2 sm:hidden">
            <?php $sm=['pending'=>'bg-amber-100 text-amber-700','disetujui'=>'bg-blue-100 text-blue-700','ditolak'=>'bg-red-100 text-red-700','dikembalikan'=>'bg-green-100 text-green-700'];
            foreach($loans as $l): ?>
            <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-gray-700/40 rounded-xl">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate"><?= esc($l['judul_buku']) ?></div>
                    <div class="text-xs text-gray-500"><?= date('d/m/Y',strtotime($l['tanggal_pinjam'])) ?> – <?= date('d/m/Y',strtotime($l['tanggal_kembali_rencana'])) ?></div>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $sm[$l['status']]??'bg-gray-100 text-gray-700' ?>"><?= ucfirst($l['status']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-sm min-w-[400px]">
                <thead><tr class="text-xs text-gray-400 uppercase border-b border-gray-100 dark:border-gray-700">
                    <th class="pb-2 text-left">Buku</th><th class="pb-2 text-left">Pinjam</th><th class="pb-2 text-left">Status</th><th class="pb-2 text-right">Denda</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php foreach($loans as $l): ?>
                <tr><td class="py-2 font-medium text-gray-800 dark:text-gray-200 max-w-[160px] truncate"><?= esc($l['judul_buku']) ?></td>
                <td class="py-2 text-gray-500"><?= date('d/m/Y',strtotime($l['tanggal_pinjam'])) ?></td>
                <td class="py-2"><span class="px-2 py-0.5 rounded-full text-xs <?= $sm[$l['status']]??'bg-gray-100 text-gray-700' ?>"><?= ucfirst($l['status']) ?></span></td>
                <td class="py-2 text-right <?= $l['denda']>0?'text-red-600 font-semibold':'text-gray-400' ?>"><?= $l['denda']>0?'Rp'.number_format($l['denda'],0,',','.'):'–' ?></td></tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
</div>
