@extends('layouts.app')

@section('title', 'Data Anggota')
@section('icon', 'fa-users')

@section('actions')
<a href="{{ route('members.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
    <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Anggota
</a>
@endsection

@section('content')

<div class="alert alert-info py-2 mb-3 small">
    <i class="fas fa-info-circle mr-1"></i>
    Tabel dilengkapi fitur <strong>search</strong>, <strong>filter status</strong>,
    <strong>sort</strong>, <strong>pagination</strong> &amp; <strong>export</strong>.
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-users mr-1"></i> Daftar Anggota ({{ $members->count() }} data)
        </h6>
    </div>
    <div class="card-body">
        @if($members->count() > 0)
        <div class="table-responsive">
            <table id="table-members" class="table table-bordered table-hover nowrap" style="width:100%">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>NIK/NIM</th>
                        <th>Jenis Kelamin</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th class="text-center">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 0; @endphp
                    @foreach($members as $member)
                    @php $i++; @endphp
                    <tr>
                        <td>{{ $i }}</td>
                        <td><code>{{ $member->kode_anggota }}</code></td>
                        <td><strong>{{ $member->nama }}</strong></td>
                        <td>{{ $member->nik_nim ?? '-' }}</td>
                        <td>{{ $member->jenis_kelamin ? $member->jenis_kelamin_label : '-' }}</td>
                        <td>{{ $member->telepon ?? '-' }}</td>
                        <td class="small">{{ $member->email ?? '-' }}</td>
                        <td class="text-center">
                            @if($member->status === 'aktif')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('members.destroy', $member) }}" method="POST"
                                      onsubmit="return confirm('Hapus anggota &quot;{{ $member->nama }}&quot;?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-users fa-4x mb-3"></i>
            <h5>Belum ada data anggota</h5>
            <a href="{{ route('members.create') }}" class="btn btn-primary mt-2">
                <i class="fas fa-plus"></i> Tambah Anggota Pertama
            </a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
$(function () {
    var table = $('#table-members').DataTable($.extend({}, window.dtDefaultOptions, {
        order: [[2, 'asc']],          // sort by nama asc
        columns: [
            { searchable: false, orderable: false },   // #
            null,   // Kode
            null,   // Nama
            null,   // NIK/NIM
            null,   // JK
            null,   // Telepon
            null,   // Email
            null,   // Status
            { searchable: false, orderable: false }    // Aksi
        ]
    }));

    // Filter status via dropdown
    var statusCol = table.column(7);
    var selectHtml = '<label class="me-2 small">Filter Status: </label>' +
        '<select id="filter-status" class="form-select form-select-sm d-inline-block" style="width:auto">' +
        '<option value="">- Semua -</option>' +
        '<option value="Aktif">Aktif</option>' +
        '<option value="Nonaktif">Nonaktif</option>' +
        '</select>';

    $('#table-members_filter').prepend(selectHtml);

    $('#filter-status').on('change', function () {
        statusCol.search(this.value).draw();
    });
});
</script>
@endpush

@endsection
