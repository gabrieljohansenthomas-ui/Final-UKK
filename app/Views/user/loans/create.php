<?php $page_title = 'Ajukan Peminjaman'; ?>
<div class="py-3 max-w-lg">
<a href="<?= base_url('/user/catalog/detail/'.$book['id_buku']) ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-emerald-500 mb-4 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Kembali
</a>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="h-1 bg-gradient-to-r from-emerald-400 to-teal-500"></div>
    <div class="p-5">
        <h2 class="text-base sm:text-lg font-bold text-gray-800 dark:text-white mb-5">Form Peminjaman</h2>

        <!-- Book preview card -->
        <div class="flex gap-3 p-3.5 bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/30 rounded-xl mb-5">
            <img src="<?= base_url('uploads/covers/'.($book['gambar']??'no-cover.png')) ?>"
                 class="w-14 h-20 object-cover rounded-xl flex-shrink-0 shadow-sm"
                 style="object-fit:cover"
                 onerror="this.src='https://via.placeholder.com/56x80/e2e8f0/94a3b8?text=📖'">
            <div class="min-w-0">
                <h3 class="font-bold text-sm text-gray-800 dark:text-white leading-snug"><?= esc($book['judul_buku']) ?></h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><?= esc($book['pengarang']) ?></p>
                <div class="flex items-center gap-1.5 mt-2">
                    <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                        <?= $book['stok']-$book['dipinjam'] ?> dari <?= $book['stok'] ?> tersedia
                    </span>
                </div>
            </div>
        </div>

        <?= form_open('/user/loans/store', ['class'=>'space-y-4']) ?>
        <?= csrf_field() ?>
        <input type="hidden" name="id_buku" value="<?= $book['id_buku'] ?>">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">
                    Tanggal Pinjam <span class="text-red-400 font-normal normal-case">*</span>
                </label>
                <input type="date" name="tanggal_pinjam"
                       value="<?= date('Y-m-d') ?>"
                       min="<?= date('Y-m-d') ?>"
                       class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">
                    Rencana Kembali <span class="text-red-400 font-normal normal-case">*</span>
                </label>
                <input type="date" name="tanggal_kembali_rencana"
                       value="<?= date('Y-m-d', strtotime('+7 days')) ?>"
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                       class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
        </div>

        <!-- Info box -->
        <div class="flex gap-3 p-3.5 bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/30 rounded-xl text-xs text-blue-700 dark:text-blue-300">
            <i class="fas fa-info-circle flex-shrink-0 mt-0.5 text-blue-500"></i>
            <p>Peminjaman akan diproses oleh admin. Denda keterlambatan <strong>Rp 1.000/hari</strong>.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <button type="submit"
                    class="flex-1 h-12 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm
                           flex items-center justify-center gap-2 transition-colors shadow-md shadow-emerald-200 dark:shadow-none">
                <i class="fas fa-paper-plane"></i> Ajukan Peminjaman
            </button>
            <a href="<?= base_url('/user/catalog/detail/'.$book['id_buku']) ?>"
               class="h-12 px-6 flex items-center justify-center bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium text-sm rounded-xl transition-colors">
                Batal
            </a>
        </div>
        <?= form_close() ?>
    </div>
</div>
</div>
