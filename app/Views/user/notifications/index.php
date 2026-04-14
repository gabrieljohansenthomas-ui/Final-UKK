<?php $page_title = 'Notifikasi'; ?>
<div class="py-3 space-y-4 max-w-2xl">

<div class="flex items-center justify-between">
    <div>
        <h2 class="text-base sm:text-lg font-bold text-gray-800 dark:text-white">Notifikasi</h2>
        <p class="text-xs text-gray-500 dark:text-gray-400"><?= count($notifs) ?> notifikasi</p>
    </div>
    <?php $unread = array_filter($notifs, fn($n)=>!$n['is_read']); ?>
    <?php if(!empty($unread)): ?>
    <?= form_open('/user/notifications/read-all') ?>
    <?= csrf_field() ?>
    <button type="submit" class="inline-flex items-center gap-1.5 text-xs text-emerald-600 hover:text-emerald-700 font-semibold bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 px-3 py-2 rounded-xl transition-colors">
        <i class="fas fa-check-double text-xs"></i> Tandai Semua Dibaca
    </button>
    <?= form_close() ?>
    <?php endif; ?>
</div>

<?php if(empty($notifs)): ?>
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-14 text-center">
    <i class="fas fa-bell text-4xl text-gray-200 dark:text-gray-700 mb-3 block"></i>
    <p class="text-gray-500 font-medium">Tidak ada notifikasi</p>
</div>
<?php else: ?>

<!-- Unread count badge -->
<?php if(!empty($unread)): ?>
<div class="flex items-center gap-2 px-4 py-2.5 bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/30 rounded-xl text-sm text-emerald-700 dark:text-emerald-300">
    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse flex-shrink-0"></div>
    <span><?= count($unread) ?> notifikasi belum dibaca</span>
</div>
<?php endif; ?>

<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm divide-y divide-gray-100 dark:divide-gray-700">
    <?php foreach($notifs as $n): ?>
    <a href="<?= base_url('/user/notifications/read/'.$n['id_notifikasi']) ?>"
       class="flex items-start gap-3 sm:gap-4 px-4 py-4 transition-colors
              <?= !$n['is_read']
                  ? 'bg-emerald-50 dark:bg-emerald-900/10 hover:bg-emerald-100 dark:hover:bg-emerald-900/20'
                  : 'hover:bg-gray-50 dark:hover:bg-gray-700/30' ?>">

        <!-- Icon -->
        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                    <?= !$n['is_read']
                        ? 'bg-emerald-100 dark:bg-emerald-900/40'
                        : 'bg-gray-100 dark:bg-gray-700' ?>">
            <i class="fas fa-bell text-sm <?= !$n['is_read']?'text-emerald-600 dark:text-emerald-400':'text-gray-400 dark:text-gray-500' ?>"></i>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
                <h4 class="font-semibold text-sm leading-snug <?= !$n['is_read']?'text-gray-900 dark:text-white':'text-gray-600 dark:text-gray-400' ?>">
                    <?= esc($n['judul']) ?>
                </h4>
                <?php if(!$n['is_read']): ?>
                <span class="flex-shrink-0 w-2.5 h-2.5 bg-emerald-500 rounded-full mt-1"></span>
                <?php endif; ?>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                <?= esc($n['pesan']) ?>
            </p>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1.5 flex items-center gap-1">
                <i class="fas fa-clock text-[9px]"></i>
                <?= date('d M Y · H:i', strtotime($n['created_at'])) ?>
            </p>
        </div>

        <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 text-xs flex-shrink-0 mt-1 self-center"></i>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>
</div>
