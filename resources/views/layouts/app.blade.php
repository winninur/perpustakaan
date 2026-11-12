<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') &middot; SIP - Sistem Informasi Perpustakaan</title>

    <!-- SB Admin 2 CSS (via CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- DataTables + Buttons (Bootstrap 5 styling) -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    @stack('styles')
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('partials.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                @include('partials.navbar')

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas @yield('icon', 'fa-tachometer-alt') text-primary mr-2"></i>
                            @yield('title', 'Dashboard')
                        </h1>
                        @yield('actions')
                    </div>

                    <!-- Flash Messages -->
                    @include('partials.flash')

                    <!-- Main Content -->
                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            @include('partials.footer')

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- jQuery (harus sebelum SB Admin 2 & DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SB Admin 2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/js/sb-admin-2.min.js"></script>

    <!-- DataTables core + Bootstrap 5 -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <!-- DataTables Buttons extension -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <!-- JSZip & pdfmake untuk export Excel & PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <!-- DataTables Responsive -->
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- Global DataTables config -->
    <script>
        window.dtDefaultOptions = {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
            order: [[0, 'desc']],
            responsive: true,
            autoWidth: false,
            dom: '<"row align-items-center mb-3"<"col-md-6"B><"col-md-6 text-end"f>>' +
                 'rtip',
            buttons: [
                { extend: 'copy',   text: '<i class="fas fa-copy"></i> Copy',   className: 'btn btn-sm btn-outline-secondary' },
                { extend: 'csv',    text: '<i class="fas fa-file-csv"></i> CSV', className: 'btn btn-sm btn-outline-primary' },
                { extend: 'excel',  text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-sm btn-outline-success' },
                { extend: 'pdf',    text: '<i class="fas fa-file-pdf"></i> PDF', className: 'btn btn-sm btn-outline-danger' },
                { extend: 'print',  text: '<i class="fas fa-print"></i> Print', className: 'btn btn-sm btn-outline-info' }
            ]
        };
    </script>

    @stack('scripts')
</body>

</html>
