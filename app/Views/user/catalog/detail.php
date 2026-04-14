<?php $page_title = esc($book['judul_buku']); ?>
<div class="py-3 max-w-4xl space-y-4">
<a href="<?= base_url('/user/catalog') ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-emerald-500 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Kembali ke Katalog
</a>

<!-- Main info block — stack on mobile, side by side on md -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

    <!-- Cover + Borrow -->
    <div class="md:col-span-1 space-y-3">
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
            <?php $available = ($book['stok'] - $book['dipinjam']) > 0; ?>
            <div class="relative" style="height:220px">
                <img src="<?= base_url('uploads/covers/'.($book['gambar']??'no-cover.png')) ?>"
                     class="w-full h-full object-cover"
                     onerror="this.src='https://via.placeholder.com/300x220/e2e8f0/94a3b8?text=📖'">
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-3">
                    <span class="text-white text-xs font-bold px-2.5 py-1 rounded-full <?= $available?'bg-green-500':'bg-red-500' ?>">
                        <?= $available ? $book['stok']-$book['dipinjam'].' tersedia' : 'Tidak tersedia' ?>
                    </span>
                </div>
            </div>
            <div class="p-4">
                <?php if($available): ?>
                <a href="<?= base_url('/user/loans/create/'.$book['id_buku']) ?>"
                   class="w-full h-11 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl flex items-center justify-center gap-2 transition-colors text-sm shadow-md shadow-emerald-200 dark:shadow-none">
                    <i class="fas fa-hand-holding-heart"></i> Pinjam Sekarang
                </a>
                <?php else: ?>
                <button disabled class="w-full h-11 bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-500 font-semibold rounded-xl flex items-center justify-center gap-2 text-sm cursor-not-allowed">
                    <i class="fas fa-ban"></i> Stok Habis
                </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Detail info cards -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm">
            <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3">Info Buku</h4>
            <dl class="space-y-2 text-sm">
                <?php $inf=[['Pengarang',$book['pengarang']],['Penerbit',$book['penerbit']??null],['Tahun',$book['tahun_terbit']??null],['ISBN',$book['isbn']??null],['Kategori',$book['nama_kategori']??null],['Total Pinjam',$book['total_pinjam'].' kali']];
                foreach($inf as [$dt,$dd]): if(!$dd)continue; ?>
                <div class="flex items-start justify-between gap-2">
                    <dt class="text-gray-500 dark:text-gray-400 flex-shrink-0"><?= $dt ?></dt>
                    <dd class="font-medium text-gray-800 dark:text-gray-200 text-right leading-snug"><?= esc($dd) ?></dd>
                </div>
                <?php endforeach; ?>
            </dl>
        </div>
    </div>

    <!-- Book details + Reviews -->
    <div class="md:col-span-2 space-y-4">
        <!-- Title + Rating -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm">
            <?php if($book['nama_kategori']): ?>
            <span class="inline-block mb-2 text-xs bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 px-3 py-1 rounded-full font-semibold"><?= esc($book['nama_kategori']) ?></span>
            <?php endif; ?>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white leading-tight mb-1"><?= esc($book['judul_buku']) ?></h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4"><?= esc($book['pengarang']) ?></p>

            <!-- Rating summary -->
            <div class="flex items-center gap-4 p-3 sm:p-4 bg-amber-50 dark:bg-amber-900/10 rounded-xl border border-amber-100 dark:border-amber-800/30 mb-4">
                <div class="text-center flex-shrink-0">
                    <div class="text-3xl sm:text-4xl font-bold text-amber-500"><?= number_format($book['avg_rating'],1) ?></div>
                    <div class="flex items-center gap-0.5 justify-center mt-1">
                        <?php $rat=round($book['avg_rating']); for($s=1;$s<=5;$s++): ?>
                        <i class="fas fa-star text-xs <?= $s<=$rat?'text-yellow-400':'text-gray-300 dark:text-gray-600' ?>"></i>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <div><span class="font-semibold text-gray-800 dark:text-gray-200"><?= $book['total_ulasan'] ?></span> ulasan</div>
                    <div><span class="font-semibold text-gray-800 dark:text-gray-200"><?= $book['total_pinjam'] ?></span> kali dipinjam</div>
                </div>
            </div>

            <?php if($book['deskripsi']): ?>
            <div>
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Deskripsi</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed"><?= nl2br(esc($book['deskripsi'])) ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Review form -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm"
             x-data="reviewForm(<?= $myUlasan ? json_encode($myUlasan) : 'null' ?>)">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2 text-sm sm:text-base">
                <i class="fas fa-star text-yellow-400"></i>
                <?= $myUlasan ? 'Ulasan Anda' : 'Tulis Ulasan' ?>
            </h3>
            <?= form_open('/user/catalog/review/'.$book['id_buku'], ['class'=>'space-y-4']) ?>
            <?= csrf_field() ?>

            <!-- Star picker -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Rating <span class="text-red-400 font-normal normal-case">*</span></label>
                <div class="flex items-center gap-2">
                    <?php for($s=1;$s<=5;$s++): ?>
                    <button type="button" @click="rating=<?= $s ?>" @mouseover="hover=<?= $s ?>" @mouseleave="hover=0"
                            :class="(hover||rating) >= <?= $s ?> ? 'text-yellow-400 scale-110' : 'text-gray-300 dark:text-gray-600'"
                            class="text-3xl sm:text-4xl transition-all duration-100 focus:outline-none leading-none">★</button>
                    <?php endfor; ?>
                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2 min-w-[80px]" x-text="labels[hover||rating]||''"></span>
                </div>
                <input type="hidden" name="rating" :value="rating" required>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Komentar</label>
                <textarea name="komentar" rows="3" x-model="komentar" placeholder="Bagikan pendapat Anda..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none placeholder:text-gray-400"></textarea>
            </div>

            <button type="submit" :disabled="!rating"
                    :class="rating ? 'bg-emerald-600 hover:bg-emerald-700 cursor-pointer shadow-sm' : 'bg-gray-200 dark:bg-gray-700 text-gray-400 cursor-not-allowed'"
                    class="w-full sm:w-auto h-11 px-6 text-white font-semibold text-sm rounded-xl flex items-center justify-center gap-2 transition-all">
                <i class="fas fa-paper-plane text-sm"></i>
                <?= $myUlasan ? 'Perbarui Ulasan' : 'Kirim Ulasan' ?>
            </button>
            <?= form_close() ?>
        </div>

        <!-- Reviews list -->
        <?php if(!empty($ulasanList)): ?>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4 text-sm sm:text-base">
                Ulasan Pembaca (<?= count($ulasanList) ?>)
            </h3>
            <div class="space-y-4">
                <?php foreach($ulasanList as $u): ?>
                <div class="flex gap-3 pb-4 border-b border-gray-100 dark:border-gray-700 last:border-0 last:pb-0">
                    <img src="<?= base_url('uploads/profiles/'.($u['foto_profil']??'default.png')) ?>"
                         class="w-9 h-9 rounded-full object-cover flex-shrink-0"
                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($u['nama_lengkap']) ?>&size=36&background=10b981&color=fff'">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <span class="font-semibold text-sm text-gray-800 dark:text-gray-200"><?= esc($u['nama_lengkap']) ?></span>
                            <span class="text-xs text-gray-400"><?= date('d M Y',strtotime($u['created_at'])) ?></span>
                        </div>
                        <div class="flex items-center gap-0.5 my-1">
                            <?php for($s=1;$s<=5;$s++): ?>
                            <i class="fas fa-star text-xs <?= $s<=$u['rating']?'text-yellow-400':'text-gray-200 dark:text-gray-600' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <?php if($u['komentar']): ?>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed"><?= esc($u['komentar']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
</div>

<script>
function reviewForm(existing){
    return{
        rating: existing?.rating ?? 0,
        hover: 0,
        komentar: existing?.komentar ?? '',
        labels: ['','Buruk','Kurang','Cukup','Bagus','Sangat Bagus'],
    }
}
</script>
