<!DOCTYPE html>
<html lang="id" x-data="appLayout()" :class="{'dark':darkMode}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#059669">
    <title>Perpustakaan Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={darkMode:'class'}</script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        [x-cloak]{display:none!important}
        ::-webkit-scrollbar{width:4px;height:4px}
        ::-webkit-scrollbar-thumb{background:#94a3b8;border-radius:4px}
        .dark ::-webkit-scrollbar-thumb{background:#475569}
        .nav-link{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;font-size:14px;font-weight:500;transition:all .2s;width:100%;text-decoration:none}
        .nav-link:hover{background:rgba(255,255,255,.1)}
        .nav-link.active{background:rgba(255,255,255,.18);color:#fff}
        .sidebar-slide{transition:transform .28s cubic-bezier(.4,0,.2,1)}
        .table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
        .line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
    </style>
</head>
<body class="h-full bg-slate-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition-colors duration-300">

<div class="flex h-dvh overflow-hidden" x-data="{sidebarOpen:false}">

    <!-- Overlay -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 lg:hidden"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen?'translate-x-0':'-translate-x-full'"
           class="sidebar-slide fixed lg:static inset-y-0 left-0 z-40 w-[260px] flex-shrink-0
                  bg-gradient-to-b from-emerald-600 via-emerald-700 to-emerald-900
                  dark:from-gray-800 dark:via-gray-850 dark:to-gray-900
                  flex flex-col shadow-2xl lg:translate-x-0">

        <div class="flex items-center justify-between px-4 py-4 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center shadow-inner">
                    <i class="fas fa-book-reader text-white text-base"></i>
                </div>
                <div>
                    <p class="text-white font-bold text-sm leading-tight">Perpustakaan</p>
                    <p class="text-emerald-200 text-[11px]">Digital</p>
                </div>
            </div>
            <button @click="sidebarOpen=false" class="lg:hidden p-2 rounded-lg text-emerald-200 hover:bg-white/10">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto">
            <?php function uActive($s){$u=service('uri');return strpos($u->getPath(),$s)!==false?'active text-white':'text-emerald-100';} ?>
            <a href="<?= base_url('/user/dashboard') ?>" class="nav-link <?= uActive('dashboard') ?>">
                <i class="fas fa-home w-5 text-center text-sm opacity-80"></i><span>Dashboard</span>
            </a>
            <p class="text-[10px] text-emerald-300 uppercase tracking-widest font-bold px-3 pt-4 pb-1">Katalog</p>
            <a href="<?= base_url('/user/catalog') ?>" class="nav-link <?= uActive('catalog') ?>">
                <i class="fas fa-search w-5 text-center text-sm opacity-80"></i><span>Cari Buku</span>
            </a>
            <p class="text-[10px] text-emerald-300 uppercase tracking-widest font-bold px-3 pt-4 pb-1">Peminjaman</p>
            <a href="<?= base_url('/user/loans') ?>" class="nav-link <?= uActive('loans') ?>">
                <i class="fas fa-book-open w-5 text-center text-sm opacity-80"></i><span>Riwayat Pinjam</span>
            </a>
            <p class="text-[10px] text-emerald-300 uppercase tracking-widest font-bold px-3 pt-4 pb-1">Akun</p>
            <a href="<?= base_url('/user/notifications') ?>" class="nav-link <?= uActive('notifications') ?>">
                <i class="fas fa-bell w-5 text-center text-sm opacity-80"></i>
                <span>Notifikasi</span>
                <?php if(($unreadNotifCount??0)>0): ?>
                <span class="ml-auto bg-red-500 text-white text-[10px] font-bold rounded-full px-1.5 py-0.5 leading-none"><?= $unreadNotifCount ?></span>
                <?php endif; ?>
            </a>
        </nav>

        <div class="px-3 pb-4 pt-2 border-t border-white/10 space-y-0.5">
            <button @click="toggleDark()" class="nav-link text-emerald-100">
                <i :class="darkMode?'fas fa-sun':'fas fa-moon'" class="w-5 text-center text-sm opacity-80"></i>
                <span x-text="darkMode?'Mode Terang':'Mode Gelap'"></span>
            </button>
            <a href="<?= base_url('/user/profile') ?>" class="nav-link <?= uActive('profile') ?> text-emerald-100">
                <i class="fas fa-user-circle w-5 text-center text-sm opacity-80"></i><span>Profil Saya</span>
            </a>
            <a href="<?= base_url('/logout') ?>" class="nav-link text-emerald-100 hover:text-red-300">
                <i class="fas fa-sign-out-alt w-5 text-center text-sm opacity-80"></i><span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        <!-- Topbar -->
        <header class="flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700
                       shadow-sm z-20 px-3 sm:px-5 h-14 flex items-center justify-between gap-2">
            <div class="flex items-center gap-2.5 min-w-0">
                <button @click="sidebarOpen=true"
                        class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl
                               bg-slate-100 dark:bg-gray-700 text-gray-500
                               hover:bg-emerald-50 hover:text-emerald-600 transition-colors flex-shrink-0">
                    <i class="fas fa-bars text-sm"></i>
                </button>
                <h1 class="text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-200 truncate">
                    <?= $page_title ?? 'Dashboard' ?>
                </h1>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <?php if(($unreadNotifCount??0)>0): ?>
                <a href="<?= base_url('/user/notifications') ?>"
                   class="w-8 h-8 flex items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 relative">
                    <i class="fas fa-bell text-sm"></i>
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center"><?= min($unreadNotifCount,9) ?></span>
                </a>
                <?php endif; ?>
                <?php $foto=session('foto_profil')?:'default.png'; ?>
                <img src="<?= base_url('uploads/profiles/'.$foto) ?>"
                     class="w-8 h-8 rounded-full object-cover ring-2 ring-emerald-300 flex-shrink-0"
                     onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode(session('nama_lengkap')) ?>&background=10b981&color=fff&size=32'">
                <div class="hidden sm:block">
                    <p class="text-xs font-semibold text-gray-700 dark:text-gray-200 leading-tight truncate max-w-[100px]"><?= session('nama_lengkap') ?></p>
                    <p class="text-[10px] text-emerald-500 leading-tight">Anggota</p>
                </div>
            </div>
        </header>

        <!-- Alerts -->
        <div class="flex-shrink-0 px-3 sm:px-5 pt-3 space-y-2">
            <?php foreach(['success'=>['green','check-circle'],'error'=>['red','exclamation-circle'],'info'=>['blue','info-circle']] as $type=>[$c,$icon]): ?>
            <?php if($msg=session()->getFlashdata($type)): ?>
            <div x-data="{s:true}" x-show="s" x-init="setTimeout(()=>s=false,5000)" x-cloak
                 class="flex items-start gap-3 px-4 py-3 rounded-xl border text-sm
                        bg-<?=$c?>-50 dark:bg-<?=$c?>-900/20 border-<?=$c?>-200 dark:border-<?=$c?>-700/50
                        text-<?=$c?>-800 dark:text-<?=$c?>-300">
                <i class="fas fa-<?=$icon?> mt-0.5 flex-shrink-0 text-<?=$c?>-500"></i>
                <span class="flex-1 leading-snug"><?= esc($msg) ?></span>
                <button @click="s=false" class="text-<?=$c?>-400 flex-shrink-0"><i class="fas fa-times text-xs"></i></button>
            </div>
            <?php endif; endforeach; ?>
            <?php if($errs=session()->getFlashdata('errors')): ?>
            <div class="px-4 py-3 rounded-xl border bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-700/50 text-red-800 dark:text-red-300 text-sm">
                <ul class="space-y-0.5 list-disc list-inside">
                    <?php foreach((array)$errs as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>

        <main class="flex-1 overflow-y-auto px-3 sm:px-5 pt-2 pb-20 lg:pb-6">
            <?= view($content_view ?? '', $this->data ?? []) ?>
        </main>
    </div>
</div>

<!-- Bottom Nav (mobile) -->
<nav class="lg:hidden fixed bottom-0 inset-x-0 z-20 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-xl">
    <div class="flex">
        <?php
        $nav=[
            ['/user/dashboard','fa-home','Beranda'],
            ['/user/catalog','fa-search','Katalog'],
            ['/user/loans','fa-book-open','Pinjam'],
            ['/user/notifications','fa-bell','Notifikasi'],
            ['/user/profile','fa-user','Profil'],
        ];
        $curPath=service('uri')->getPath();
        foreach($nav as [$h,$ic,$lb]):
            $seg=explode('/',$h)[2]??'';
            $on=$seg&&strpos($curPath,$seg)!==false;
        ?>
        <a href="<?= base_url($h) ?>"
           class="flex-1 flex flex-col items-center justify-center py-2 h-[56px] gap-0.5 relative
                  <?= $on?'text-emerald-600 dark:text-emerald-400':'text-gray-400 dark:text-gray-500' ?>
                  hover:text-emerald-500 transition-colors">
            <?php if($on): ?><span class="absolute top-0 inset-x-3 h-0.5 bg-emerald-500 rounded-b-full"></span><?php endif; ?>
            <?php if($lb==='Notifikasi'&&($unreadNotifCount??0)>0): ?>
            <span class="relative"><i class="fas <?=$ic?> text-lg leading-none"></i><span class="absolute -top-1 -right-1.5 w-3.5 h-3.5 bg-red-500 text-white text-[8px] font-bold rounded-full flex items-center justify-center"><?= min($unreadNotifCount,9) ?></span></span>
            <?php else: ?>
            <i class="fas <?=$ic?> text-lg leading-none"></i>
            <?php endif; ?>
            <span class="text-[10px] font-medium leading-none"><?= $lb ?></span>
        </a>
        <?php endforeach; ?>
    </div>
</nav>

<script>
function appLayout(){
    return{
        darkMode:localStorage.getItem('darkMode')==='true',
        toggleDark(){this.darkMode=!this.darkMode;localStorage.setItem('darkMode',this.darkMode)}
    }
}
</script>
</body>
</html>
