<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-book-reader"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SIP PERPUS</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard (Bisa diakses Admin & Pustakawan) -->
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Menu Utama
    </div>

    @if(auth()->check() && auth()->user()->role == 'admin')
        {{-- ================= MENU KHUSUS ADMIN ================= --}}
        
        <!-- Nav Item - Master Kategori -->
        <li class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('categories.index') }}">
                <i class="fas fa-fw fa-tags"></i>
                <span>Kelola Kategori</span>
            </a>
        </li>

        <!-- Nav Item - Master Buku -->
        <li class="nav-item {{ request()->routeIs('books.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('books.index') }}">
                <i class="fas fa-fw fa-book"></i>
                <span>Kelola Data Buku</span>
            </a>
        </li>

        <!-- Nav Item - Master Anggota -->
        <li class="nav-item {{ request()->routeIs('members.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('members.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Kelola Anggota</span>
            </a>
        </li>

        <!-- Nav Item - Transaksi Peminjaman -->
        <li class="nav-item {{ request()->routeIs('loans.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('loans.index') }}">
                <i class="fas fa-fw fa-handshake"></i>
                <span>Data Peminjaman</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Pengaturan
        </div>

        <!-- Nav Item - Manajemen User -->
        <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="fas fa-fw fa-user-cog"></i>
                <span>Manajemen User</span>
            </a>
        </li>

    @else
        {{-- ================= MENU KHUSUS PUSTAKAWAN ================= --}}
        
        <!-- Nav Item - Katalog Buku -->
        <li class="nav-item {{ request()->routeIs('books.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('books.index') }}">
                <i class="fas fa-fw fa-book-open"></i>
                <span>Katalog Buku</span>
            </a>
        </li>

        <!-- Nav Item - Peminjaman Saya -->
        <li class="nav-item {{ request()->routeIs('loans.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('loans.index') }}">
                <i class="fas fa-fw fa-receipt"></i>
                <span>Peminjaman Saya</span>
            </a>
        </li>

        <!-- Nav Item - Daftar Buku Favorit Aman -->
        @if(Route::has('favorites.index'))
        <li class="nav-item {{ request()->routeIs('favorites.*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('favorites.index') }}">
        <i class="fas fa-fw fa-bookmark"></i>
        <span>Buku Favorit</span>
    </a>
</li>
        @endif
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->