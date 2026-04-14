<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Lupa Password – Perpustakaan Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-amber-400 via-orange-500 to-rose-500 flex items-center justify-center p-4">
<div class="w-full max-w-sm">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-amber-400 via-orange-400 to-rose-400"></div>
        <div class="px-6 py-7">
            <div class="text-center mb-6">
                <div class="inline-flex w-14 h-14 bg-amber-100 rounded-2xl items-center justify-center mb-3">
                    <i class="fas fa-key text-2xl text-amber-600"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-800">Lupa Password</h1>
                <p class="text-sm text-gray-500 mt-1">Masukkan email untuk reset password</p>
            </div>

            <?php if($info=session()->getFlashdata('info')): ?>
            <div class="flex items-start gap-2 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl mb-4 text-sm">
                <i class="fas fa-info-circle mt-0.5 flex-shrink-0"></i><span><?= esc($info) ?></span>
            </div>
            <?php endif; ?>

            <?= form_open('/forgot-password', ['class'=>'space-y-4']) ?>
            <?= csrf_field() ?>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3.5 flex items-center text-gray-400 pointer-events-none"><i class="fas fa-envelope text-sm"></i></span>
                    <input type="email" name="email" placeholder="email@example.com" required
                           class="w-full h-11 pl-10 pr-4 rounded-xl border border-gray-200 bg-gray-50 text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent placeholder:text-gray-400 transition">
                </div>
            </div>
            <button type="submit" class="w-full h-11 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl text-sm flex items-center justify-center gap-2 transition-colors">
                <i class="fas fa-paper-plane"></i> Kirim Link Reset
            </button>
            <?= form_close() ?>

            <a href="<?= base_url('/login') ?>" class="flex items-center justify-center gap-2 mt-4 text-sm text-gray-500 hover:text-amber-600 transition-colors">
                <i class="fas fa-arrow-left text-xs"></i> Kembali ke Login
            </a>
        </div>
    </div>
</div>
</body>
</html>
