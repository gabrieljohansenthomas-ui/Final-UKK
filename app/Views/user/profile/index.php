<?php $page_title = 'Profil Saya'; ?>
<div class="py-3 max-w-xl space-y-4">
<h2 class="text-base sm:text-lg font-bold text-gray-800 dark:text-white">Profil Saya</h2>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="h-1 bg-gradient-to-r from-emerald-400 to-teal-500"></div>
    <div class="p-5 sm:p-6">
        <?= form_open_multipart('/user/profile/update', ['class'=>'space-y-5']) ?>
        <?= csrf_field() ?>

        <!-- Avatar -->
        <div class="flex items-center gap-4" x-data="imgPrev()">
            <div class="relative group cursor-pointer flex-shrink-0" @click="$refs.fi.click()">
                <img :src="preview||'<?= base_url('uploads/profiles/'.($user['foto_profil']??'default.png')) ?>'"
                     class="w-20 h-20 rounded-full object-cover ring-4 ring-emerald-100 dark:ring-emerald-900/30 shadow-md"
                     onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($user['nama_lengkap']) ?>&background=10b981&color=fff&size=80'">
                <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-camera text-white text-lg"></i>
                </div>
            </div>
            <div>
                <p class="font-bold text-gray-800 dark:text-white text-sm"><?= esc($user['nama_lengkap']) ?></p>
                <p class="text-xs text-gray-400 mt-0.5">@<?= esc($user['username']) ?></p>
                <button type="button" @click="$refs.fi.click()" class="mt-2 text-xs text-emerald-500 hover:text-emerald-600 font-semibold underline">Ganti foto</button>
                <p class="text-[10px] text-gray-400">JPG/PNG/GIF · Maks 2MB</p>
            </div>
            <input type="file" name="foto_profil" x-ref="fi" @change="pick($event)" class="hidden" accept="image/*">
        </div>

        <!-- Basic info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Nama Lengkap <span class="text-red-400 font-normal normal-case">*</span></label>
                <input type="text" name="nama_lengkap" value="<?= old('nama_lengkap',$user['nama_lengkap']) ?>" required
                       class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Email <span class="text-red-400 font-normal normal-case">*</span></label>
                <input type="email" name="email" value="<?= old('email',$user['email']) ?>" required
                       class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Username</label>
                <input type="text" value="@<?= esc($user['username']) ?>" disabled
                       class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-400 text-sm cursor-not-allowed">
            </div>
        </div>

        <!-- Member data -->
        <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Data Anggota</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">NIM / NIS</label>
                    <input type="text" name="nim_nis" value="<?= old('nim_nis',$anggota['nim_nis']??'') ?>"
                           class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">No. Telepon</label>
                    <input type="tel" name="no_telp" value="<?= old('no_telp',$anggota['no_telp']??'') ?>"
                           class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Alamat</label>
                    <textarea name="alamat" rows="2"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none"><?= old('alamat',$anggota['alamat']??'') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Password -->
        <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Ganti Password <span class="font-normal normal-case">(opsional)</span></p>
            <div x-data="{show:false}">
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Password Baru</label>
                <div class="relative">
                    <input :type="show?'text':'password'" name="password" placeholder="Kosongkan jika tidak diubah"
                           class="w-full h-11 px-4 pr-11 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 placeholder:text-gray-400">
                    <button type="button" @click="show=!show" class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 px-1">
                        <i :class="show?'fas fa-eye-slash':'fas fa-eye'" class="text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <button type="submit"
                class="w-full sm:w-auto h-11 px-8 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800
                       text-white font-bold text-sm rounded-xl flex items-center justify-center gap-2 transition-colors shadow-sm">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>
        <?= form_close() ?>
    </div>
</div>
</div>
<script>
function imgPrev(){return{preview:null,pick(e){const f=e.target.files[0];if(f){const r=new FileReader;r.onload=ev=>this.preview=ev.target.result;r.readAsDataURL(f)}}}}
</script>
