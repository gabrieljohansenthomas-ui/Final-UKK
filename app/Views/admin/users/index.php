<?php $page_title='Manajemen User'; ?>
<div class="py-3 space-y-4">
<div class="flex items-center justify-between">
    <h2 class="text-base sm:text-lg font-bold text-gray-800 dark:text-white">Daftar User</h2>
    <a href="<?= base_url('/admin/users/create') ?>" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm">
        <i class="fas fa-plus text-xs"></i><span class="hidden sm:inline">Tambah </span>User
    </a>
</div>
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
    <?= form_open('/admin/users',['method'=>'get','class'=>'flex flex-col sm:flex-row gap-3']) ?>
    <div class="flex-1 relative">
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none"><i class="fas fa-search text-sm"></i></span>
        <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Nama, username, email..."
               class="w-full h-10 pl-9 pr-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder:text-gray-400">
    </div>
    <div class="flex gap-2">
        <select name="role" class="flex-1 h-10 px-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
            <option value="">Semua Role</option>
            <option value="admin" <?= $role==='admin'?'selected':'' ?>>Admin</option>
            <option value="user" <?= $role==='user'?'selected':'' ?>>User</option>
        </select>
        <select name="status" class="flex-1 h-10 px-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
            <option value="">Status</option>
            <option value="1" <?= $status==='1'?'selected':'' ?>>Aktif</option>
            <option value="0" <?= $status==='0'?'selected':'' ?>>Nonaktif</option>
        </select>
    </div>
    <div class="flex gap-2">
        <button type="submit" class="flex-1 sm:flex-none h-10 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-xl"><i class="fas fa-filter"></i></button>
        <a href="<?= base_url('/admin/users') ?>" class="flex-1 sm:flex-none h-10 px-4 flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm rounded-xl">Reset</a>
    </div>
    <?= form_close() ?>
</div>

<!-- Mobile cards -->
<div class="space-y-3 sm:hidden">
<?php foreach($users as $u): ?>
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4" x-data="{del:false}">
    <div class="flex items-center gap-3 mb-3">
        <img src="<?= base_url('uploads/profiles/'.($u['foto_profil']??'default.png')) ?>" class="w-11 h-11 rounded-full object-cover flex-shrink-0" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($u['nama_lengkap']) ?>&background=6366f1&color=fff&size=44'">
        <div class="flex-1 min-w-0">
            <div class="font-semibold text-sm text-gray-800 dark:text-gray-200"><?= esc($u['nama_lengkap']) ?></div>
            <div class="text-xs text-gray-400">@<?= esc($u['username']) ?></div>
        </div>
        <div class="flex flex-col items-end gap-1">
            <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $u['role']==='admin'?'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300':'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' ?>"><?= ucfirst($u['role']) ?></span>
            <span class="px-2 py-0.5 rounded-full text-xs <?= $u['status']?'bg-green-100 text-green-700':'bg-red-100 text-red-700' ?>"><?= $u['status']?'Aktif':'Nonaktif' ?></span>
        </div>
    </div>
    <div class="flex gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
        <a href="<?= base_url('/admin/users/edit/'.$u['id_user']) ?>" class="flex-1 h-8 flex items-center justify-center gap-1.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded-lg text-xs font-medium"><i class="fas fa-edit text-[10px]"></i>Edit</a>
        <?= form_open('/admin/users/toggle/'.$u['id_user'],['class'=>'flex-1']) ?><?= csrf_field() ?>
        <button type="submit" class="w-full h-8 flex items-center justify-center gap-1.5 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-lg text-xs font-medium"><i class="fas fa-power-off text-[10px]"></i><?= $u['status']?'Nonaktifkan':'Aktifkan' ?></button>
        <?= form_close() ?>
        <?php if($u['id_user']!=session('id_user')): ?>
        <button @click="del=true" class="w-8 h-8 flex items-center justify-center bg-red-50 dark:bg-red-900/20 text-red-500 rounded-lg"><i class="fas fa-trash text-xs"></i></button>
        <?php endif; ?>
    </div>
    <div x-show="del" x-cloak class="fixed inset-0 bg-black/50 flex items-end sm:items-center justify-center z-50 p-4" @click.self="del=false">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 w-full max-w-sm shadow-2xl text-center">
            <p class="font-bold text-gray-800 dark:text-white mb-2">Hapus user ini?</p>
            <p class="text-sm text-gray-500 mb-4"><?= esc($u['nama_lengkap']) ?></p>
            <div class="flex gap-3">
                <button @click="del=false" class="flex-1 h-11 bg-gray-100 dark:bg-gray-700 text-gray-700 rounded-xl text-sm">Batal</button>
                <?= form_open('/admin/users/delete/'.$u['id_user'],['class'=>'flex-1']) ?><?= csrf_field() ?>
                <button type="submit" class="w-full h-11 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold">Hapus</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>

<!-- Desktop table -->
<div class="hidden sm:block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-sm min-w-[640px]">
        <thead class="bg-slate-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
            <tr class="text-xs text-gray-500 dark:text-gray-400 uppercase">
                <th class="px-4 py-3 text-left">User</th>
                <th class="px-4 py-3 text-left">Email</th>
                <th class="px-4 py-3 text-center">Role</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3 text-left">Last Login</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
        <?php foreach($users as $u): ?>
        <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/30" x-data="{del:false}">
            <td class="px-4 py-3">
                <div class="flex items-center gap-2.5">
                    <img src="<?= base_url('uploads/profiles/'.($u['foto_profil']??'default.png')) ?>" class="w-8 h-8 rounded-full object-cover flex-shrink-0" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($u['nama_lengkap']) ?>&background=6366f1&color=fff&size=32'">
                    <div><div class="font-medium text-gray-800 dark:text-gray-200 text-sm"><?= esc($u['nama_lengkap']) ?></div><div class="text-xs text-gray-400">@<?= esc($u['username']) ?></div></div>
                </div>
            </td>
            <td class="px-4 py-3 text-gray-500 text-sm"><?= esc($u['email']) ?></td>
            <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $u['role']==='admin'?'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300':'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' ?>"><?= ucfirst($u['role']) ?></span></td>
            <td class="px-4 py-3 text-center">
                <?= form_open('/admin/users/toggle/'.$u['id_user'],['class'=>'inline']) ?><?= csrf_field() ?>
                <button type="submit" class="px-2 py-0.5 rounded-full text-xs font-medium cursor-pointer <?= $u['status']?'bg-green-100 text-green-700 hover:bg-green-200':'bg-red-100 text-red-700 hover:bg-red-200' ?> transition-colors"><?= $u['status']?'Aktif':'Nonaktif' ?></button>
                <?= form_close() ?>
            </td>
            <td class="px-4 py-3 text-xs text-gray-400"><?= $u['last_login']?date('d/m/Y H:i',strtotime($u['last_login'])):'–' ?></td>
            <td class="px-4 py-3 text-center">
                <div class="flex items-center justify-center gap-1.5">
                    <a href="<?= base_url('/admin/users/edit/'.$u['id_user']) ?>" class="w-8 h-8 flex items-center justify-center rounded-lg text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors"><i class="fas fa-edit text-xs"></i></a>
                    <?php if($u['id_user']!=session('id_user')): ?>
                    <button @click="del=true" class="w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors"><i class="fas fa-trash text-xs"></i></button>
                    <?php endif; ?>
                </div>
                <div x-show="del" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" @click.self="del=false">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-sm w-full shadow-2xl text-center">
                        <p class="font-bold text-gray-800 dark:text-white mb-4">Hapus "<?= esc($u['nama_lengkap']) ?>"?</p>
                        <div class="flex gap-3">
                            <button @click="del=false" class="flex-1 h-11 bg-gray-100 dark:bg-gray-700 text-gray-700 rounded-xl text-sm">Batal</button>
                            <?= form_open('/admin/users/delete/'.$u['id_user'],['class'=>'flex-1']) ?><?= csrf_field() ?>
                            <button type="submit" class="w-full h-11 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold">Hapus</button>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
