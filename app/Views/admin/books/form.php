<?php
$isEdit   = isset($book);
$page_title = $isEdit ? 'Edit Buku' : 'Tambah Buku';
$action   = $isEdit ? base_url('/admin/books/update/'.$book['id_buku']) : base_url('/admin/books/store');
?>
<div class="py-3 max-w-2xl">
<div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-4">
    <a href="<?= base_url('/admin/books') ?>" class="flex items-center gap-1 hover:text-indigo-500 transition-colors">
        <i class="fas fa-arrow-left text-xs"></i> Buku
    </a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 dark:text-gray-200 font-medium"><?= $page_title ?></span>
</div>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-4 sm:p-6">
        <h2 class="text-base sm:text-lg font-bold text-gray-800 dark:text-white mb-5"><?= $page_title ?></h2>

        <?= form_open_multipart($action, ['class'=>'space-y-5']) ?>
        <?= csrf_field() ?>

        <!-- Image upload — full width on mobile -->
        <div x-data="imgPreview()" class="space-y-3">
            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Cover Buku</label>
            <div class="flex items-start gap-4">
                <div class="relative group cursor-pointer flex-shrink-0" @click="$refs.fi.click()" @dragover.prevent @drop.prevent="dropFile($event)">
                    <img :src="preview || '<?= base_url('uploads/covers/'.($book['gambar']??'no-cover.png')) ?>'"
                         class="w-24 h-32 sm:w-28 sm:h-36 object-cover rounded-xl shadow-md border-2 border-gray-100 dark:border-gray-700"
                         onerror="this.src='https://via.placeholder.com/112x144/e2e8f0/94a3b8?text=📖'">
                    <div class="absolute inset-0 bg-black/40 rounded-xl flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <i class="fas fa-camera text-white text-xl mb-1"></i>
                        <span class="text-white text-[10px] font-medium">Ganti</span>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Klik gambar atau drag &amp; drop file gambar</p>
                    <p class="text-xs text-gray-400">JPG, PNG, GIF • Maks. 2MB</p>
                    <button type="button" @click="$refs.fi.click()" class="mt-3 text-xs text-indigo-500 hover:text-indigo-600 font-medium underline">
                        Pilih File
                    </button>
                </div>
            </div>
            <input type="file" name="gambar" x-ref="fi" @change="pick($event)" class="hidden" accept="image/*">
        </div>

        <div class="border-t border-gray-100 dark:border-gray-700 pt-4 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">Judul Buku <span class="text-red-400 normal-case font-normal">*</span></label>
                <input type="text" name="judul_buku" value="<?= old('judul_buku',$book['judul_buku']??'') ?>" required
                       class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">Pengarang <span class="text-red-400 normal-case font-normal">*</span></label>
                    <input type="text" name="pengarang" value="<?= old('pengarang',$book['pengarang']??'') ?>" required
                           class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">Penerbit</label>
                    <input type="text" name="penerbit" value="<?= old('penerbit',$book['penerbit']??'') ?>"
                           class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" value="<?= old('tahun_terbit',$book['tahun_terbit']??'') ?>" min="1900" max="<?= date('Y') ?>"
                           class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">ISBN</label>
                    <input type="text" name="isbn" value="<?= old('isbn',$book['isbn']??'') ?>"
                           class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">Kategori</label>
                    <select name="id_kategori" class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Pilih --</option>
                        <?php foreach($kategoris as $k): ?>
                        <option value="<?= $k['id_kategori'] ?>" <?= old('id_kategori',$book['id_kategori']??'')==$k['id_kategori']?'selected':'' ?>><?= esc($k['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">Stok <span class="text-red-400 normal-case font-normal">*</span></label>
                    <input type="number" name="stok" value="<?= old('stok',$book['stok']??1) ?>" min="0" required
                           class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"><?= old('deskripsi',$book['deskripsi']??'') ?></textarea>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <button type="submit" class="flex-1 sm:flex-none h-11 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl transition-colors flex items-center justify-center gap-2">
                <i class="fas <?= $isEdit?'fa-save':'fa-plus' ?>"></i> <?= $isEdit?'Simpan Perubahan':'Tambah Buku' ?>
            </button>
            <a href="<?= base_url('/admin/books') ?>"
               class="h-11 px-6 flex items-center justify-center bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium text-sm rounded-xl transition-colors">
                Batal
            </a>
        </div>
        <?= form_close() ?>
    </div>
</div>
</div>
<script>
function imgPreview(){return{preview:null,pick(e){const f=e.target.files[0];if(f){const r=new FileReader;r.onload=e=>this.preview=e.target.result;r.readAsDataURL(f)}},dropFile(e){const f=e.dataTransfer.files[0];if(f&&f.type.startsWith('image/')){const dt=new DataTransfer;dt.items.add(f);this.$refs.fi.files=dt.files;this.pick({target:{files:[f]}})}}}
}
</script>
