<?php $page_title='Anggota'; ?>
<div class="py-3 space-y-4">
<div class="flex items-center justify-between">
    <h2 class="text-base sm:text-lg font-bold text-gray-800 dark:text-white">Daftar Anggota</h2>
    <span class="text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-xl border border-gray-200 dark:border-gray-700"><?= count($members) ?> anggota</span>
</div>
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
    <?= form_open('/admin/members',['method'=>'get','class'=>'flex gap-3']) ?>
    <div class="flex-1 relative">
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none"><i class="fas fa-search text-sm"></i></span>
        <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Nama, email, NIM/NIS..."
               class="w-full h-10 pl-9 pr-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder:text-gray-400">
    </div>
    <button type="submit" class="h-10 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors"><i class="fas fa-search sm:mr-1"></i><span class="hidden sm:inline"> Cari</span></button>
    <a href="<?= base_url('/admin/members') ?>" class="h-10 px-3 flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm rounded-xl transition-colors">Reset</a>
    <?= form_close() ?>
</div>

<!-- Card Grid mobile, Table desktop -->
<div class="grid grid-cols-1 sm:hidden gap-3">
<?php foreach($members as $m): ?>
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 flex items-center gap-3">
    <img src="<?= base_url('uploads/profiles/'.($m['foto_profil']??'default.png')) ?>"
         class="w-12 h-12 rounded-full object-cover border-2 border-gray-100 flex-shrink-0"
         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($m['nama_lengkap']) ?>&background=6366f1&color=fff&size=48'">
    <div class="flex-1 min-w-0">
        <div class="font-semibold text-sm text-gray-800 dark:text-gray-200"><?= esc($m['nama_lengkap']) ?></div>
        <div class="text-xs text-gray-500">@<?= esc($m['username']) ?></div>
        <?php if($m['nim_nis']): ?><div class="text-xs text-indigo-600 dark:text-indigo-400 font-medium mt-0.5"><?= esc($m['nim_nis']) ?></div><?php endif; ?>
    </div>
    <a href="<?= base_url('/admin/members/detail/'.$m['id_anggota']) ?>" class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
        <i class="fas fa-eye text-sm"></i>
    </a>
</div>
<?php endforeach; ?>
</div>

<div class="hidden sm:block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-sm min-w-[600px]">
        <thead class="bg-slate-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
            <tr class="text-xs text-gray-500 dark:text-gray-400 uppercase">
                <th class="px-4 py-3 text-left">Anggota</th>
                <th class="px-4 py-3 text-left">Email</th>
                <th class="px-4 py-3 text-left">NIM/NIS</th>
                <th class="px-4 py-3 text-left">No Telp</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
        <?php foreach($members as $m): ?>
        <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/30">
            <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                    <img src="<?= base_url('uploads/profiles/'.($m['foto_profil']??'default.png')) ?>" class="w-8 h-8 rounded-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($m['nama_lengkap']) ?>&background=6366f1&color=fff&size=32'">
                    <div><div class="font-medium text-gray-800 dark:text-gray-200 text-sm"><?= esc($m['nama_lengkap']) ?></div><div class="text-xs text-gray-400">@<?= esc($m['username']) ?></div></div>
                </div>
            </td>
            <td class="px-4 py-3 text-gray-500 text-sm"><?= esc($m['email']) ?></td>
            <td class="px-4 py-3 text-gray-500 text-sm"><?= esc($m['nim_nis']??'–') ?></td>
            <td class="px-4 py-3 text-gray-500 text-sm"><?= esc($m['no_telp']??'–') ?></td>
            <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $m['status']?'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300':'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' ?>"><?= $m['status']?'Aktif':'Nonaktif' ?></span></td>
            <td class="px-4 py-3 text-center">
                <a href="<?= base_url('/admin/members/detail/'.$m['id_anggota']) ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 rounded-lg text-xs font-medium">
                    <i class="fas fa-eye text-[10px]"></i> Detail
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
