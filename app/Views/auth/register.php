<!DOCTYPE html>
<html lang="id" x-data="{darkMode:localStorage.getItem('darkMode')==='true',showPw:false,showCpw:false}" :class="{dark:darkMode}">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Daftar – Perpustakaan Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={darkMode:'class'}</script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-700 dark:from-gray-900 dark:to-gray-800 p-4">

<div class="max-w-lg mx-auto py-6">
    <!-- Back link -->
    <a href="<?= base_url('/login') ?>" class="inline-flex items-center gap-2 text-white/80 hover:text-white text-sm mb-4 transition-colors">
        <i class="fas fa-arrow-left text-xs"></i> Sudah punya akun? Login
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-500"></div>

        <div class="px-5 py-6 sm:px-7">
            <!-- Header -->
            <div class="flex items-center gap-3 mb-6">
                <div class="w-11 h-11 bg-emerald-100 dark:bg-emerald-900/40 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-plus text-emerald-600 dark:text-emerald-400 text-lg"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-800 dark:text-white">Buat Akun Baru</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Perpustakaan Digital</p>
                </div>
            </div>

            <!-- Errors -->
            <?php if($errs=session()->getFlashdata('errors')): ?>
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl mb-4 text-sm">
                <ul class="list-disc list-inside space-y-0.5">
                    <?php foreach((array)$errs as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?= form_open('/register', ['class'=>'space-y-4']) ?>
            <?= csrf_field() ?>

            <!-- Input helper macro -->
            <?php
            $inp = fn($n,$l,$t='text',$ph='',$req=true,$val='') =>
                '<div>'.
                '<label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">'.$l.($req?' <span class="text-red-400 normal-case font-normal">*</span>':'').'</label>'.
                '<input type="'.$t.'" name="'.$n.'" value="'.old($n,$val).'" placeholder="'.$ph.'"'.($req?' required':'').
                ' class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder:text-gray-400 transition">'.
                '</div>';
            ?>

            <?= $inp('nama_lengkap','Nama Lengkap','text','Nama lengkap Anda',true) ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?= $inp('username','Username','text','username unik',true) ?>
                <?= $inp('email','Email','email','email@example.com',true) ?>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Password -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Password <span class="text-red-400 normal-case font-normal">*</span></label>
                    <div class="relative">
                        <input :type="showPw?'text':'password'" name="password" placeholder="Min. 6 karakter" required
                               class="w-full h-11 px-4 pr-11 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder:text-gray-400 transition">
                        <button type="button" @click="showPw=!showPw" class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 px-1">
                            <i :class="showPw?'fas fa-eye-slash':'fas fa-eye'" class="text-sm"></i>
                        </button>
                    </div>
                </div>
                <!-- Konfirmasi -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Konfirmasi <span class="text-red-400 normal-case font-normal">*</span></label>
                    <div class="relative">
                        <input :type="showCpw?'text':'password'" name="confirm_password" placeholder="Ulangi password" required
                               class="w-full h-11 px-4 pr-11 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder:text-gray-400 transition">
                        <button type="button" @click="showCpw=!showCpw" class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 px-1">
                            <i :class="showCpw?'fas fa-eye-slash':'fas fa-eye'" class="text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="flex items-center gap-3 py-1">
                <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
                <span class="text-xs text-gray-400 font-medium">Data Anggota (opsional)</span>
                <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?= $inp('nim_nis','NIM / NIS','text','Nomor induk',false) ?>
                <?= $inp('no_telp','No. Telepon','text','08xxxxxxxxxx',false) ?>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Alamat</label>
                <textarea name="alamat" rows="2" placeholder="Alamat lengkap"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder:text-gray-400 transition resize-none"><?= old('alamat') ?></textarea>
            </div>

            <button type="submit"
                    class="w-full h-12 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800
                           text-white font-semibold rounded-xl text-sm
                           flex items-center justify-center gap-2 transition-colors shadow-md shadow-emerald-200 dark:shadow-none mt-1">
                <i class="fas fa-user-plus"></i> Buat Akun
            </button>
            <?= form_close() ?>
        </div>
    </div>
</div>
</body>
</html>
