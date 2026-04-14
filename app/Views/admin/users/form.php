<?php
$isEdit    = isset($user);
$page_title = $isEdit ? 'Edit User' : 'Tambah User';
$action    = $isEdit ? base_url('/admin/users/update/'.$user['id_user']) : base_url('/admin/users/store');
?>
<div class="py-3 max-w-2xl">
<a href="<?= base_url('/admin/users') ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-500 transition-colors mb-4">
    <i class="fas fa-arrow-left text-xs"></i> Kembali
</a>
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="h-1 bg-gradient-to-r from-indigo-500 to-violet-500"></div>
    <div class="p-5 sm:p-6">
        <h2 class="text-base sm:text-lg font-bold text-gray-800 dark:text-white mb-5"><?= $page_title ?></h2>
        <?= form_open($action, ['class'=>'space-y-4']) ?>
        <?= csrf_field() ?>

        <div>
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Nama Lengkap <span class="text-red-400 font-normal normal-case">*</span></label>
            <input type="text" name="nama_lengkap" value="<?= old('nama_lengkap',$user['nama_lengkap']??'') ?>" required
                   class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Username <span class="text-red-400 font-normal normal-case">*</span></label>
                <input type="text" name="username" value="<?= old('username',$user['username']??'') ?>" required
                       class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Email <span class="text-red-400 font-normal normal-case">*</span></label>
                <input type="email" name="email" value="<?= old('email',$user['email']??'') ?>" required
                       class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div x-data="{show:false}">
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">
                    Password <?= $isEdit ? '<span class="font-normal normal-case text-gray-400">(kosongkan jika tidak diubah)</span>' : '<span class="text-red-400 font-normal normal-case">*</span>' ?>
                </label>
                <div class="relative">
                    <input :type="show?'text':'password'" name="password" <?= $isEdit?'':'required' ?> placeholder="Min. 6 karakter"
                           class="w-full h-11 px-4 pr-10 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button type="button" @click="show=!show" class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i :class="show?'fas fa-eye-slash':'fas fa-eye'" class="text-sm"></i>
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Role <span class="text-red-400 font-normal normal-case">*</span></label>
                <select name="role" class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="user" <?= old('role',$user['role']??'user')==='user'?'selected':'' ?>>User</option>
                    <option value="admin" <?= old('role',$user['role']??'')==='admin'?'selected':'' ?>>Admin</option>
                </select>
            </div>
            <?php if($isEdit): ?>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Status</label>
                <select name="status" class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="1" <?= ($user['status']??1)?'selected':'' ?>>Aktif</option>
                    <option value="0" <?= !($user['status']??1)?'selected':'' ?>>Nonaktif</option>
                </select>
            </div>
            <?php endif; ?>
        </div>

        <!-- Data Anggota -->
        <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Data Anggota <span class="font-normal normal-case">(untuk role User)</span></p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">NIM / NIS</label>
                    <input type="text" name="nim_nis" value="<?= old('nim_nis',$anggota['nim_nis']??'') ?>"
                           class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">No. Telepon</label>
                    <input type="text" name="no_telp" value="<?= old('no_telp',$anggota['no_telp']??'') ?>"
                           class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Alamat</label>
                    <textarea name="alamat" rows="2"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"><?= old('alamat',$anggota['alamat']??'') ?></textarea>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <button type="submit" class="flex-1 sm:flex-none h-11 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl flex items-center justify-center gap-2 transition-colors shadow-sm">
                <i class="fas <?= $isEdit?'fa-save':'fa-plus' ?>"></i>
                <?= $isEdit ? 'Simpan Perubahan' : 'Tambah User' ?>
            </button>
            <a href="<?= base_url('/admin/users') ?>"
               class="h-11 px-6 flex items-center justify-center bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium text-sm rounded-xl transition-colors">
                Batal
            </a>
        </div>
        <?= form_close() ?>
    </div>
</div>
</div>
