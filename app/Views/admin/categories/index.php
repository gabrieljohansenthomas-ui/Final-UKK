<?php $page_title='Kategori'; ?>
<div class="py-3 space-y-4 max-w-3xl">
<div class="flex items-center justify-between">
    <h2 class="text-base sm:text-lg font-bold text-gray-800 dark:text-white">Daftar Kategori</h2>
    <button onclick="document.getElementById('addCat').classList.remove('hidden')"
            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-sm">
        <i class="fas fa-plus text-xs"></i><span class="hidden sm:inline">Tambah</span>
    </button>
</div>

<!-- Add Modal -->
<div id="addCat" class="hidden fixed inset-0 bg-black/50 flex items-end sm:items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 w-full max-w-md shadow-2xl">
        <h3 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2"><i class="fas fa-tags text-indigo-500"></i>Tambah Kategori</h3>
        <?= form_open('/admin/categories/store') ?><?= csrf_field() ?>
        <div class="space-y-3">
            <input type="text" name="nama_kategori" required placeholder="Nama kategori"
                   class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <textarea name="deskripsi" rows="2" placeholder="Deskripsi (opsional)"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
        </div>
        <div class="flex gap-3 mt-4">
            <button type="submit" class="flex-1 h-11 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold">Simpan</button>
            <button type="button" onclick="document.getElementById('addCat').classList.add('hidden')" class="flex-1 h-11 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm">Batal</button>
        </div>
        <?= form_close() ?>
    </div>
</div>

<!-- Card Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
<?php foreach($kategoris as $k): ?>
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm" x-data="{edit:false,del:false}">
    <div class="flex items-start justify-between gap-2 mb-2">
        <div class="flex-1 min-w-0">
            <div class="font-semibold text-gray-800 dark:text-gray-200 text-sm"><?= esc($k['nama_kategori']) ?></div>
            <?php if($k['deskripsi']): ?><div class="text-xs text-gray-500 mt-0.5 truncate"><?= esc($k['deskripsi']) ?></div><?php endif; ?>
        </div>
        <span class="flex-shrink-0 px-2.5 py-0.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-full text-xs font-bold"><?= $k['jumlah_buku'] ?> buku</span>
    </div>
    <div class="flex gap-2 mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
        <button @click="edit=true" class="flex-1 h-8 flex items-center justify-center gap-1.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded-lg text-xs font-medium"><i class="fas fa-edit text-[10px]"></i>Edit</button>
        <button @click="del=true" class="flex-1 h-8 flex items-center justify-center gap-1.5 bg-red-50 dark:bg-red-900/20 text-red-500 dark:text-red-400 rounded-lg text-xs font-medium <?= $k['jumlah_buku']>0?'opacity-50 cursor-not-allowed':'' ?>" <?= $k['jumlah_buku']>0?'disabled':'' ?>><i class="fas fa-trash text-[10px]"></i>Hapus</button>
    </div>
    <!-- Edit -->
    <div x-show="edit" x-cloak class="fixed inset-0 bg-black/50 flex items-end sm:items-center justify-center z-50 p-4" @click.self="edit=false">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 w-full max-w-md shadow-2xl">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4">Edit Kategori</h3>
            <?= form_open('/admin/categories/update/'.$k['id_kategori']) ?><?= csrf_field() ?>
            <div class="space-y-3">
                <input type="text" name="nama_kategori" value="<?= esc($k['nama_kategori']) ?>" required class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <textarea name="deskripsi" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"><?= esc($k['deskripsi']??'') ?></textarea>
            </div>
            <div class="flex gap-3 mt-4">
                <button type="submit" class="flex-1 h-11 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold">Simpan</button>
                <button type="button" @click="edit=false" class="flex-1 h-11 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm">Batal</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
    <!-- Delete -->
    <div x-show="del" x-cloak class="fixed inset-0 bg-black/50 flex items-end sm:items-center justify-center z-50 p-4" @click.self="del=false">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 w-full max-w-sm shadow-2xl text-center">
            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-3"><i class="fas fa-trash text-red-500 text-xl"></i></div>
            <p class="font-bold text-gray-800 dark:text-white mb-1">Hapus Kategori?</p>
            <p class="text-sm text-gray-500 mb-4">"<?= esc($k['nama_kategori']) ?>"</p>
            <?php if($k['jumlah_buku']>0): ?>
            <p class="text-sm text-red-500 mb-3">Tidak bisa dihapus — masih digunakan oleh <?= $k['jumlah_buku'] ?> buku.</p>
            <button @click="del=false" class="w-full h-11 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm">Tutup</button>
            <?php else: ?>
            <div class="flex gap-3">
                <button @click="del=false" class="flex-1 h-11 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm">Batal</button>
                <?= form_open('/admin/categories/delete/'.$k['id_kategori'],['class'=>'flex-1']) ?><?= csrf_field() ?>
                <button type="submit" class="w-full h-11 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold">Hapus</button>
                <?= form_close() ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>
</div>
