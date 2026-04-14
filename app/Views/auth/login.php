<!DOCTYPE html>
<html lang="id" x-data="{darkMode:localStorage.getItem('darkMode')==='true',showPw:false}" :class="{dark:darkMode}">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0,viewport-fit=cover">
    <meta name="theme-color" content="#4338ca">
    <title>Login – Perpustakaan Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={darkMode:'class'}</script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-600 via-violet-600 to-purple-700 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 flex flex-col items-center justify-center p-4 gap-4">

    <!-- Card -->
    <div class="w-full max-w-sm bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">

        <!-- Top accent -->
        <div class="h-1.5 bg-gradient-to-r from-indigo-500 via-violet-500 to-purple-500"></div>

        <div class="px-6 py-7 sm:px-8">
            <!-- Brand -->
            <div class="text-center mb-7">
                <div class="inline-flex w-14 h-14 bg-indigo-100 dark:bg-indigo-900/50 rounded-2xl items-center justify-center mb-4 shadow-sm">
                    <i class="fas fa-book-open text-2xl text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">Perpustakaan Digital</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Masuk ke akun Anda</p>
            </div>

            <!-- Alerts -->
            <?php if($s=session()->getFlashdata('success')): ?>
            <div class="flex items-center gap-2 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-3 py-2.5 rounded-xl mb-4 text-sm">
                <i class="fas fa-check-circle flex-shrink-0"></i><span><?= esc($s) ?></span>
            </div>
            <?php endif; ?>
            <?php if($e=session()->getFlashdata('error')): ?>
            <div class="flex items-center gap-2 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-3 py-2.5 rounded-xl mb-4 text-sm">
                <i class="fas fa-exclamation-circle flex-shrink-0"></i><span><?= esc($e) ?></span>
            </div>
            <?php endif; ?>

            <!-- Form -->
            <?= form_open('/login', ['class'=>'space-y-4']) ?>
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Username / Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3.5 flex items-center text-gray-400 pointer-events-none">
                        <i class="fas fa-user text-sm"></i>
                    </span>
                    <input type="text" name="login" value="<?= old('login') ?>"
                           placeholder="username atau email"
                           autocomplete="username"
                           class="w-full h-11 pl-10 pr-4 rounded-xl border border-gray-200 dark:border-gray-600
                                  bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                  placeholder:text-gray-400 transition" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3.5 flex items-center text-gray-400 pointer-events-none">
                        <i class="fas fa-lock text-sm"></i>
                    </span>
                    <input :type="showPw?'text':'password'" name="password"
                           placeholder="••••••••"
                           autocomplete="current-password"
                           class="w-full h-11 pl-10 pr-11 rounded-xl border border-gray-200 dark:border-gray-600
                                  bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                  placeholder:text-gray-400 transition" required>
                    <button type="button" @click="showPw=!showPw"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 px-1">
                        <i :class="showPw?'fas fa-eye-slash':'fas fa-eye'" class="text-sm"></i>
                    </button>
                </div>
            </div>

            <button type="submit"
                    class="w-full h-11 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
                           text-white font-semibold rounded-xl text-sm
                           flex items-center justify-center gap-2 transition-colors shadow-md shadow-indigo-200 dark:shadow-none mt-1">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </button>
            <?= form_close() ?>

            <!-- Links -->
            <div class="mt-5 space-y-2 text-center text-sm">
                <p><a href="<?= base_url('/forgot-password') ?>" class="text-indigo-500 hover:text-indigo-600 font-medium">Lupa password?</a></p>
                <p class="text-gray-500 dark:text-gray-400">
                    Belum punya akun?
                    <a href="<?= base_url('/register') ?>" class="text-indigo-600 font-semibold hover:underline">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Demo hint 
    <div class="text-center text-white/70 text-xs space-y-0.5">
        <p class="font-medium text-white/90">Demo Login:</p>
        <p>Admin: <code class="bg-white/10 px-1.5 py-0.5 rounded">admin</code> / <code class="bg-white/10 px-1.5 py-0.5 rounded">password</code></p>
        <p>User: <code class="bg-white/10 px-1.5 py-0.5 rounded">budi</code> / <code class="bg-white/10 px-1.5 py-0.5 rounded">password</code></p>
    </div>
    -->

    <!-- Dark toggle -->
    <button @click="darkMode=!darkMode;localStorage.setItem('darkMode',darkMode)"
            class="text-white/60 hover:text-white text-xs flex items-center gap-1.5 transition-colors">
        <i :class="darkMode?'fas fa-sun':'fas fa-moon'" class="text-sm"></i>
        <span x-text="darkMode?'Mode Terang':'Mode Gelap'"></span>
    </button>
</body>
</html>
