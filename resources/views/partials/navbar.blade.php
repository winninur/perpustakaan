<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Breadcrumb -->
    <ol class="breadcrumb mb-0 ml-2 d-none d-sm-flex bg-transparent p-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">@yield('title', 'Dashboard')</li>
    </ol>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - User Info -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small font-weight-bold">
                    {{ auth()->user()->name }}
                    @if(auth()->user()->role === 'admin')
                        <span class="badge badge-danger ml-1">Admin</span>
                    @else
                        <span class="badge badge-info ml-1">User</span>
                    @endif
                </span>
                <i class="fas fa-user-circle fa-fw fa-lg text-gray-400"></i>
            </a>

            <!-- Dropdown Menu -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="userDropdown">
                <h6 class="dropdown-header">
                    <i class="fas fa-envelope mr-2 text-gray-400"></i>
                    {{ auth()->user()->email }}
                </h6>
                
                <div class="dropdown-divider"></div>

                <!-- MENU PROFIL SAYA -->
                <a class="dropdown-item" href="{{ route('profile.index') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profil Saya
                </a>

                <div class="dropdown-divider"></div>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-left">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </button>
                </form>
            </div>
        </li>

    </ul>

</nav>