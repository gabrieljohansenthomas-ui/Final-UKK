<?php $page_title = 'Katalog Buku'; ?>
<div class="py-3 space-y-4">

<!-- Search bar -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-3 sm:p-4">
    <?= form_open('/user/catalog', ['method'=>'get']) ?>
    <div class="flex gap-2">
        <div class="flex-1 relative">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none"><i class="fas fa-search text-sm"></i></span>
            <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Judul, pengarang..."
                   class="w-full h-11 pl-9 pr-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 placeholder:text-gray-400">
            <input type="hidden" name="kategori" value="<?= esc($kategori) ?>">
        </div>
        <button type="submit" class="h-11 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl transition-colors flex-shrink-0">
            <i class="fas fa-search sm:mr-1"></i><span class="hidden sm:inline"> Cari</span>
        </button>
        <?php if($search||$kategori): ?>
        <a href="<?= base_url('/user/catalog') ?>" class="h-11 px-3 flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm rounded-xl flex-shrink-0">
            <i class="fas fa-times"></i>
        </a>
        <?php endif; ?>
    </div>
    <?= form_close() ?>
</div>

<!-- Category chips — horizontal scroll on mobile -->
<div class="flex gap-2 overflow-x-auto pb-1 -mx-3 sm:mx-0 px-3 sm:px-0 sm:flex-wrap" style="-webkit-overflow-scrolling:touch">
    <a href="<?= base_url('/user/catalog?search='.urlencode($search)) ?>"
       class="flex-none h-8 px-3.5 rounded-full text-xs font-semibold transition-colors whitespace-nowrap
              <?= !$kategori ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700 hover:border-emerald-300 hover:text-emerald-600' ?>">
        Semua
    </a>
    <?php foreach($kategoris as $k): ?>
    <a href="<?= base_url('/user/catalog?kategori='.$k['id_kategori'].'&search='.urlencode($search)) ?>"
       class="flex-none h-8 px-3.5 rounded-full text-xs font-semibold transition-colors whitespace-nowrap
              <?= $kategori==$k['id_kategori'] ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700 hover:border-emerald-300 hover:text-emerald-600' ?>">
        <?= esc($k['nama_kategori']) ?>
    </a>
    <?php endforeach; ?>
</div>

<!-- Results info -->
<div class="flex items-center justify-between">
    <p class="text-xs text-gray-500 dark:text-gray-400">
        <span class="font-semibold text-gray-700 dark:text-gray-300"><?= count($books) ?></span> buku ditemukan
        <?php if($search): ?> untuk "<em><?= esc($search) ?></em>"<?php endif; ?>
    </p>
</div>

<!-- Book Grid — 2 col mobile, 3 sm, 4 md, 5 lg -->
<?php if(empty($books)): ?>
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-14 text-center">
    <i class="fas fa-search text-4xl text-gray-200 dark:text-gray-700 mb-3 block"></i>
    <p class="text-gray-500 font-medium">Buku tidak ditemukan</p>
    <p class="text-xs text-gray-400 mt-1">Coba kata kunci atau kategori lain</p>
</div>
<?php else: ?>
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
    <?php foreach($books as $book):
    $available = ($book['stok'] - $book['dipinjam']) > 0;
    $rat = round($book['avg_rating']);
    ?>
    <a href="<?= base_url('/user/catalog/detail/'.$book['id_buku']) ?>"
       class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden
              hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 group flex flex-col">
        <div class="relative overflow-hidden flex-shrink-0" style="height:150px">
            <img src="<?= base_url('uploads/covers/'.($book['gambar']??'no-cover.png')) ?>"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                 loading="lazy"
                 onerror="this.src='https://via.placeholder.com/200x150/e2e8f0/94a3b8?text=📖'">
            <?php if(!$available): ?>
            <div class="absolute inset-0 bg-black/55 flex items-center justify-center">
                <span class="text-white text-xs font-bold bg-red-500 px-2 py-1 rounded-lg">Habis</span>
            </div>
            <?php else: ?>
            <span class="absolute top-2 right-2 bg-green-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full shadow">
                <?= $book['stok']-$book['dipinjam'] ?>
            </span>
            <?php endif; ?>
        </div>
        <div class="p-3 flex flex-col flex-1 gap-1">
            <div class="text-xs sm:text-sm font-semibold text-gray-800 dark:text-gray-200 leading-snug flex-1"
                 style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                <?= esc($book['judul_buku']) ?>
            </div>
            <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 truncate"><?= esc($book['pengarang']) ?></div>
            <?php if($book['nama_kategori']): ?>
            <span class="self-start text-[10px] bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 px-2 py-0.5 rounded-full font-medium truncate max-w-full"><?= esc($book['nama_kategori']) ?></span>
            <?php endif; ?>
            <div class="flex items-center gap-0.5 mt-auto pt-1">
                <?php for($s=1;$s<=5;$s++): ?>
                <i class="fas fa-star text-[10px] <?= $s<=$rat?'text-yellow-400':'text-gray-200 dark:text-gray-600' ?>"></i>
                <?php endfor; ?>
                <span class="text-[10px] text-gray-400 ml-0.5">(<?= $book['total_ulasan'] ?>)</span>
            </div>
        </div>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>
</div>
