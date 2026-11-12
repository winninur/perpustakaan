@extends('layouts.app')

@section('title', 'Manajemen User')
@section('icon', 'fa-user-cog')

@section('actions')
<a href="{{ route('users.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
    <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah User
</a>
@endsection

@section('content')

<!-- Alert Informasi Fitur DataTables -->
<div class="alert alert-info py-2 mb-3 small">
    <i class="fas fa-info-circle mr-1"></i>
    Tabel dilengkapi fitur <strong>search</strong>, <strong>filter role</strong>, <strong>sort</strong>, <strong>pagination</strong> & <strong>export</strong>.
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-user-cog mr-1"></i> Daftar User ({{ $users->count() }} data)
        </h6>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
        <div class="table-responsive">
            <table id="table-users" class="table table-bordered table-hover nowrap align-middle" style="width:100%">
                <thead class="thead-light">
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th class="text-center">Role</th>
                        <th>Dibuat</th>
                        <th width="12%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                    <tr class="{{ $user->id === auth()->id() ? 'table-info' : '' }}">
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">
                            <strong>{{ $user->name }}</strong>
                            @if($user->id === auth()->id())
                                <span class="badge badge-info ml-1">You</span>
                            @endif
                        </td>
                        <td class="small align-middle">{{ $user->email }}</td>
                        <td class="align-middle">{{ $user->phone ?? '-' }}</td>
                        <td class="text-center align-middle">
                            @if($user->role === 'admin')
                                <span class="badge badge-danger px-2 py-1">Admin</span>
                            @else
                                <span class="badge badge-info px-2 py-1">User</span>
                            @endif
                        </td>
                        <td class="small align-middle">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</td>
                        <td class="text-center align-middle">
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                <!-- Tombol Edit -->
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning mr-1" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Tombol Hapus (Cegah Hapus Akun Sendiri) -->
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @else
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Tidak bisa menghapus akun sendiri">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-user-slash fa-4x mb-3 text-gray-300"></i>
            <h5>Belum ada data user</h5>
            <a href="{{ route('users.create') }}" class="btn btn-primary mt-2">
                <i class="fas fa-plus mr-1"></i> Tambah User Baru
            </a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#table-users')) {
        $('#table-users').DataTable().destroy();
    }

    var table = $('#table-users').DataTable($.extend({}, window.dtDefaultOptions || {}, {
        order: [[1, 'asc']], // Sort default berdasarkan nama
        columns: [
            { searchable: false, orderable: false }, // #
            null, // Nama
            null, // Email
            null, // Telepon
            null, // Role
            { searchable: false }, // Dibuat
            { searchable: false, orderable: false } // Aksi
        ]
    }));

    // Tambahkan Filter Role pada area Filter DataTables
    if ($('#filter-role').length === 0) {
        var selectHtml = '<label class="mr-2 small font-weight-bold">Filter Role: ' +
            '<select id="filter-role" class="form-control form-control-sm d-inline-block ml-1" style="width:auto">' +
            '<option value="">- Semua Role -</option>' +
            '<option value="Admin">Admin</option>' +
            '<option value="User">User</option>' +
            '</select></label>';

        $('#table-users_filter').prepend(selectHtml);

        $('#filter-role').on('change', function () {
            table.column(4).search(this.value).draw();
        });
    }
});
</script>
@endpush

@endsection 