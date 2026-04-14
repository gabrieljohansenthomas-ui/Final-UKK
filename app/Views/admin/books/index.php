<?php $page_title = 'Manajemen Buku'; ?>
<div class="py-3 space-y-4">

<!-- Header -->
<div class="flex items-center justify-between">
    <div>
        <h2 class="text-base sm:text-lg font-bold text-gray-800 dark:text-white">Daftar Buku</h2>
        <p class="text-xs text-gray-500 dark:text-gray-400"><?= count($books) ?> buku</p>
    </div>
    <a href="<?= base_url('/admin/books/create') ?>"
       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
              text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-sm">
        <i class="fas fa-plus text-xs"></i>
        <span class="hidden sm:inline">Tambah </span>Buku
    </a>
</div>

<!-- Filter -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
    <?= form_open('/admin/books', ['method'=>'get', 'class'=>'flex flex-col sm:flex-row gap-3']) ?>
    <div class="flex-1 relative">
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none"><i class="fas fa-search text-sm"></i></span>
        <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Judul, pengarang, ISBN..."
               class="w-full h-10 pl-9 pr-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder:text-gray-400">
    </div>
    <select name="kategori" class="h-10 px-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">Semua Kategori</option>
        <?php foreach($kategoris as $k): ?>
        <option value="<?= $k['id_kategori'] ?>" <?= $kategori==$k['id_kategori']?'selected':'' ?>><?= esc($k['nama_kategori']) ?></option>
        <?php endforeach; ?>
    </select>
    <div class="flex gap-2">
        <button type="submit" class="flex-1 sm:flex-none h-10 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors">
            <i class="fas fa-filter sm:mr-1"></i><span class="hidden sm:inline"> Filter</span>
        </button>
        <a href="<?= base_url('/admin/books') ?>" class="flex-1 sm:flex-none h-10 px-4 flex items-center justify-center bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 text-sm rounded-xl transition-colors">
            Reset
        </a>
    </div>
    <?= form_close() ?>
</div>

<!-- Mobile Cards / Desktop Table -->
<?php if(empty($books)): ?>
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-12 text-center">
    <i class="fas fa-book text-4xl text-gray-200 dark:text-gray-700 mb-3 block"></i>
    <p class="text-gray-400 font-medium">Tidak ada buku ditemukan</p>
</div>
<?php else: ?>

<!-- Mobile: Card Grid -->
<div class="grid grid-cols-1 sm:hidden gap-3">
    <?php foreach($books as $book):
    $avail = $book['stok'] - $book['dipinjam'];
    ?>
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 flex gap-3" x-data="{del:false}">
        <img src="<?= base_url('uploads/covers/'.($book['gambar']??'no-cover.png')) ?>"
             class="w-14 h-18 object-cover rounded-xl flex-shrink-0 shadow-sm" style="height:72px"
             onerror="this.src='https://via.placeholder.com/56x72/e2e8f0/94a3b8?text=📖'">
        <div class="flex-1 min-w-0">
            <div class="font-semibold text-gray-800 dark:text-gray-200 text-sm leading-snug"><?= esc($book['judul_buku']) ?></div>
            <div class="text-xs text-gray-500 mt-0.5"><?= esc($book['pengarang']) ?></div>
            <?php if($book['nama_kategori']): ?>
            <span class="inline-block mt-1.5 text-[10px] bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 px-2 py-0.5 rounded-full font-medium"><?= esc($book['nama_kategori']) ?></span>
            <?php endif; ?>
            <div class="flex items-center gap-3 mt-2 text-xs">
                <span class="text-gray-500">Stok: <strong class="text-gray-700 dark:text-gray-300"><?= $book['stok'] ?></strong></span>
                <span class="<?= $avail>0?'text-green-600':'text-red-500' ?> font-semibold">
                    <?= $avail>0 ? $avail.' tersedia' : 'Habis' ?>
                </span>
            </div>
        </div>
        <div class="flex flex-col gap-1.5 flex-shrink-0">
            <a href="<?= base_url('/admin/books/edit/'.$book['id_buku']) ?>"
               class="w-8 h-8 flex items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 hover:bg-indigo-100 transition-colors">
                <i class="fas fa-edit text-xs"></i>
            </a>
            <button @click="del=true"
                    class="w-8 h-8 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30 text-red-500 hover:bg-red-100 transition-colors">
                <i class="fas fa-trash text-xs"></i>
            </button>
        </div>
        <!-- Delete Modal -->
        <div x-show="del" x-cloak class="fixed inset-0 bg-black/50 flex items-end sm:items-center justify-center z-50 p-4"
             @click.self="del=false">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 w-full max-w-sm shadow-2xl">
                <div class="text-center mb-4">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-3"><i class="fas fa-trash text-red-500 text-lg"></i></div>
                    <p class="font-bold text-gray-800 dark:text-white">Hapus Buku?</p>
                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">"<?= esc($book['judul_buku']) ?>"</p>
                </div>
                <div class="flex gap-3">
                    <button @click="del=false" class="flex-1 h-11 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-medium">Batal</button>
                    <?= form_open('/admin/books/delete/'.$book['id_buku'],['class'=>'flex-1']) ?>
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full h-11 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-medium">Hapus</button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Desktop Table -->
<div class="hidden sm:block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[640px]">
            <thead class="bg-slate-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <tr class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                    <th class="px-4 py-3 text-left">Cover</th>
                    <th class="px-4 py-3 text-left">Judul / Pengarang</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-center">Stok</th>
                    <th class="px-4 py-3 text-center">Tersedia</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <?php foreach($books as $book):
            $avail=$book['stok']-$book['dipinjam']; ?>
            <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/30 transition-colors" x-data="{del:false}">
                <td class="px-4 py-3">
                    <img src="<?= base_url('uploads/covers/'.($book['gambar']??'no-cover.png')) ?>"
                         class="w-10 object-cover rounded-lg shadow-sm" style="height:52px;object-fit:cover"
                         onerror="this.src='https://via.placeholder.com/40x52/e2e8f0/94a3b8?text=📖'">
                </td>
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-800 dark:text-gray-200 truncate max-w-[200px]"><?= esc($book['judul_buku']) ?></div>
                    <div class="text-xs text-gray-400"><?= esc($book['pengarang']) ?></div>
                </td>
                <td class="px-4 py-3">
                    <?php if($book['nama_kategori']): ?>
                    <span class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-full text-xs font-medium"><?= esc($book['nama_kategori']) ?></span>
                    <?php else: ?><span class="text-gray-400 text-xs">–</span><?php endif; ?>
                </td>
                <td class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300"><?= $book['stok'] ?></td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold <?= $avail>0?'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300':'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' ?>"><?= $avail ?></span>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-1.5">
                        <a href="<?= base_url('/admin/books/edit/'.$book['id_buku']) ?>"
                           class="w-8 h-8 flex items-center justify-center rounded-lg text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" title="Edit">
                            <i class="fas fa-edit text-xs"></i>
                        </a>
                        <button @click="del=true" class="w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="Hapus">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                    <div x-show="del" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" @click.self="del=false">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-sm w-full shadow-2xl text-center">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3"><i class="fas fa-trash text-red-500 text-xl"></i></div>
                            <p class="font-bold text-gray-800 dark:text-white mb-1">Hapus Buku?</p>
                            <p class="text-sm text-gray-500 mb-4">"<?= esc($book['judul_buku']) ?>" akan dihapus.</p>
                            <div class="flex gap-3">
                                <button @click="del=false" class="flex-1 h-10 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm">Batal</button>
                                <?= form_open('/admin/books/delete/'.$book['id_buku'],['class'=>'flex-1']) ?>
                                <?= csrf_field() ?>
                                <button type="submit" class="w-full h-10 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm">Hapus</button>
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
<?php endif; ?>
</div>
