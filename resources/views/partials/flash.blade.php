@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h6 class="alert-heading">
        <i class="fas fa-exclamation-circle mr-1"></i>
        Terjadi {{ $errors->count() }} kesalahan input:
    </h6>
    <ul class="mb-0 pl-3">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <i class="fas fa-check-circle mr-2"></i>
    <strong>Berhasil!</strong> {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <i class="fas fa-exclamation-triangle mr-2"></i>
    <strong>Gagal!</strong> {{ session('error') }}
</div>
@endif

@if (session('warning'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <i class="fas fa-info-circle mr-2"></i>
    {{ session('warning') }}
</div>
@endif
