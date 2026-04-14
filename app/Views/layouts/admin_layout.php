<!DOCTYPE html>
<html lang="id" x-data="appLayout()" :class="{ 'dark': darkMode }" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#4338ca">
    <title>Perpustakaan Digital – Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        [x-cloak]{display:none!important}
        ::-webkit-scrollbar{width:4px;height:4px}
        ::-webkit-scrollbar-track{background:transparent}
        ::-webkit-scrollbar-thumb{background:#94a3b8;border-radius:4px}
        .dark ::-webkit-scrollbar-thumb{background:#475569}
        .nav-link{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;font-size:14px;font-weight:500;transition:all .2s;cursor:pointer;width:100%;color:inherit;text-decoration:none}
        .nav-link:hover{background:rgba(255,255,255,.1)}
        .nav-link.active{background:rgba(255,255,255,.18);color:#fff}
        .sidebar-slide{transition:transform .28s cubic-bezier(.4,0,.2,1)}
        .table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
    </style>
</head>
<body class="h-full bg-slate-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition-colors duration-300">

<div class="flex h-dvh overflow-hidden" x-data="{sidebarOpen:false}">

    <!-- Overlay mobile -->
    <div x-show="sidebarOpen" x-cloak
         @click="sidebarOpen=false"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 lg:hidden"></div>

    <!-- ====== SIDEBAR ====== -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="sidebar-slide fixed lg:static inset-y-0 left-0 z-40 w-[260px] flex-shrink-0
                  bg-gradient-to-b from-indigo-700 via-indigo-800 to-indigo-900
                  dark:from-gray-800 dark:via-gray-850 dark:to-gray-900
                  flex flex-col shadow-2xl lg:translate-x-0">

        <!-- Brand -->
        <div class="flex items-center justify-between px-4 pt-safe-top py-4 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center shadow-inner">
                    <i class="fas fa-book-open text-white text-base"></i>
                </div>
                <div>
                    <p class="text-white font-bold text-sm leading-tight">Perpustakaan</p>
                    <p class="text-indigo-300 text-[11px]">Digital Admin</p>
                </div>
            </div>
            <button @click="sidebarOpen=false" class="lg:hidden p-2 rounded-lg text-indigo-200 hover:bg-white/10">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <!-- Nav Links -->
        <nav class="flex-1 px-3 py-3 overflow-y-auto space-y-0.5">
            <?php function isActive($s){$u=service('uri');return strpos($u->getPath(),$s)!==false?'active text-white':'text-indigo-200';} ?>

            <a href="<?= base_url('/admin/dashboard') ?>" class="nav-link <?= isActive('dashboard') ?>">
                <i class="fas fa-chart-pie w-5 text-center text-sm opacity-80"></i><span>Dashboard</span>
            </a>

            <p class="text-[10px] text-indigo-400 uppercase tracking-widest font-bold px-3 pt-4 pb-1">Koleksi</p>
            <a href="<?= base_url('/admin/books') ?>" class="nav-link <?= isActive('books') ?>">
                <i class="fas fa-book w-5 text-center text-sm opacity-80"></i><span>Buku</span>
            </a>
            <a href="<?= base_url('/admin/categories') ?>" class="nav-link <?= isActive('categories') ?>">
                <i class="fas fa-tags w-5 text-center text-sm opacity-80"></i><span>Kategori</span>
            </a>

            <p class="text-[10px] text-indigo-400 uppercase tracking-widest font-bold px-3 pt-4 pb-1">Transaksi</p>
            <a href="<?= base_url('/admin/loans') ?>" class="nav-link <?= isActive('loans') ?>">
                <i class="fas fa-hand-holding-heart w-5 text-center text-sm opacity-80"></i><span>Peminjaman</span>
            </a>

            <p class="text-[10px] text-indigo-400 uppercase tracking-widest font-bold px-3 pt-4 pb-1">Pengguna</p>
            <a href="<?= base_url('/admin/members') ?>" class="nav-link <?= isActive('members') ?>">
                <i class="fas fa-users w-5 text-center text-sm opacity-80"></i><span>Anggota</span>
            </a>
            <a href="<?= base_url('/admin/users') ?>" class="nav-link <?= isActive('users') ?>">
                <i class="fas fa-user-cog w-5 text-center text-sm opacity-80"></i><span>Manajemen User</span>
            </a>

            <p class="text-[10px] text-indigo-400 uppercase tracking-widest font-bold px-3 pt-4 pb-1">Laporan</p>
            <a href="<?= base_url('/admin/reports') ?>" class="nav-link <?= isActive('reports') ?>">
                <i class="fas fa-file-alt w-5 text-center text-sm opacity-80"></i><span>Laporan</span>
            </a>
        </nav>

        <!-- Bottom Controls -->
        <div class="px-3 pb-4 pt-2 border-t border-white/10 space-y-0.5">
            <button @click="toggleDark()" class="nav-link text-indigo-200">
                <i :class="darkMode?'fas fa-sun':'fas fa-moon'" class="w-5 text-center text-sm opacity-80"></i>
                <span x-text="darkMode?'Mode Terang':'Mode Gelap'"></span>
            </button>
            <a href="<?= base_url('/admin/profile') ?>" class="nav-link <?= isActive('profile') ?> text-indigo-200">
                <i class="fas fa-user-circle w-5 text-center text-sm opacity-80"></i><span>Profil</span>
            </a>
            <a href="<?= base_url('/logout') ?>" class="nav-link text-indigo-200 hover:text-red-300">
                <i class="fas fa-sign-out-alt w-5 text-center text-sm opacity-80"></i><span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- ====== MAIN ====== -->
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        <!-- Topbar -->
        <header class="flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700
                       shadow-sm z-20 px-3 sm:px-5 h-14 flex items-center justify-between gap-2">
            <div class="flex items-center gap-2.5 min-w-0">
                <button @click="sidebarOpen=true"
                        class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl
                               bg-slate-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400
                               hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600
                               transition-colors flex-shrink-0">
                    <i class="fas fa-bars text-sm"></i>
                </button>
                <h1 class="text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-200 truncate">
                    <?= $page_title ?? 'Dashboard' ?>
                </h1>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <?php if(($unreadNotifCount??0)>0): ?>
                <a href="<?= base_url('/admin/loans?status=pending') ?>"
                   title="<?= $unreadNotifCount ?> pengajuan baru menunggu persetujuan"
                   class="inline-flex items-center gap-1 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50
                          text-red-600 dark:text-red-400 text-[11px] font-bold px-2.5 py-1 rounded-full
                          transition-colors cursor-pointer animate-pulse hover:animate-none">
                    <i class="fas fa-bell text-[10px]"></i>
                    <span><?= $unreadNotifCount ?></span>
                </a>
                <?php endif; ?>
                <?php $foto = session('foto_profil') ?: 'default.png'; ?>
                <img src="<?= base_url('uploads/profiles/'.$foto) ?>"
                     class="w-8 h-8 rounded-full object-cover ring-2 ring-indigo-300 flex-shrink-0"
                     onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode(session('nama_lengkap')) ?>&background=6366f1&color=fff&size=32'">
                <div class="hidden sm:block">
                    <p class="text-xs font-semibold text-gray-700 dark:text-gray-200 leading-tight truncate max-w-[100px]"><?= session('nama_lengkap') ?></p>
                    <p class="text-[10px] text-indigo-500 leading-tight">Admin</p>
                </div>
            </div>
        </header>

        <!-- Alerts -->
        <div class="flex-shrink-0 px-3 sm:px-5 pt-3 space-y-2" id="flash-area">
            <?php foreach(['success'=>['green','check-circle'],'error'=>['red','exclamation-circle'],'info'=>['blue','info-circle'],'warning'=>['amber','exclamation-triangle']] as $type=>[$c,$icon]): ?>
            <?php if($msg=session()->getFlashdata($type)): ?>
            <div x-data="{s:true}" x-show="s" x-init="setTimeout(()=>s=false,5000)" x-cloak
                 class="flex items-start gap-3 px-4 py-3 rounded-xl border text-sm
                        bg-<?=$c?>-50 dark:bg-<?=$c?>-900/20 border-<?=$c?>-200 dark:border-<?=$c?>-700/50
                        text-<?=$c?>-800 dark:text-<?=$c?>-300">
                <i class="fas fa-<?=$icon?> mt-0.5 flex-shrink-0 text-<?=$c?>-500"></i>
                <span class="flex-1 leading-snug"><?= esc($msg) ?></span>
                <button @click="s=false" class="text-<?=$c?>-400 hover:text-<?=$c?>-600 flex-shrink-0"><i class="fas fa-times text-xs"></i></button>
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

        <!-- Page content -->
        <main class="flex-1 overflow-y-auto px-3 sm:px-5 pt-2 pb-20 lg:pb-6">
            <?= view($content_view ?? '', $this->data ?? []) ?>
        </main>
    </div>
</div>

<!-- Bottom Nav (mobile only) -->
<nav class="lg:hidden fixed bottom-0 inset-x-0 z-20 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-xl">
    <div class="flex">
        <?php
        $nav=[
            ['/admin/dashboard','fa-chart-pie','Beranda'],
            ['/admin/books','fa-book','Buku'],
            ['/admin/loans','fa-exchange-alt','Pinjam'],
            ['/admin/members','fa-users','Anggota'],
            ['/admin/reports','fa-file-alt','Laporan'],
        ];
        $curPath=service('uri')->getPath();
        foreach($nav as [$h,$ic,$lb]):
            $seg=explode('/',$h)[2]??'';
            $on=$seg&&strpos($curPath,$seg)!==false;
        ?>
        <a href="<?= base_url($h) ?>"
           class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 h-[56px]
                  <?= $on?'text-indigo-600 dark:text-indigo-400':'text-gray-400 dark:text-gray-500' ?>
                  hover:text-indigo-500 transition-colors relative">
            <?php if($on): ?><span class="absolute top-0 inset-x-4 h-0.5 bg-indigo-500 rounded-b-full"></span><?php endif; ?>
            <i class="fas <?= $ic ?> text-lg leading-none"></i>
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
