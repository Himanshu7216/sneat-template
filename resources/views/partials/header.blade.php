<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />

    <title>Sneat - @yield('title')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- ================= Fonts ================= -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />

    <!-- ================= Core CSS ================= -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- ================= Vendor CSS ================= -->
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

    <!-- ================= Bootstrap ================= -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- ================= DataTables ================= -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css"
        rel="stylesheet">

    <!-- Toastr CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Toastr Custom Fixes for Bootstrap 5 & Sneat Theme -->
    <style>
        #toast-container {
            z-index: 999999 !important;
        }
        #toast-container > .toast {
            opacity: 1 !important;
            box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.2) !important;
            border-radius: 0.375rem !important;
            padding: 12px 15px 12px 50px !important;
            color: #ffffff !important;
        }
        #toast-container > .toast-success {
            background-color: #28a745 !important;
        }
        #toast-container > .toast-error {
            background-color: #dc3545 !important;
        }
        #toast-container > .toast-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }
        #toast-container > .toast-info {
            background-color: #17a2b8 !important;
        }
        #toast-container > .toast .toast-message,
        #toast-container > .toast .toast-title {
            color: inherit !important;
            opacity: 1 !important;
        }

    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom CSS (loaded last to override vendor styles) -->
    <link rel="stylesheet"
        href="{{ asset('css/breadcrumbs_navigation.css') }}">

    <!-- ================= Helper Scripts ================= -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- ================= jQuery ================= -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Duplicate (Not Recommended) -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/4.0.0/jquery.min.js"></script> --}}

    <!-- jQuery Validation -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.20.0/dist/jquery.validate.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>

 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
<script>
    window.Dropzone = window.Dropzone || {};
    window.Dropzone.autoDiscover = false;
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<style>
    body {
    background: rgb(243, 244, 245);
    height: 100%;
    color: rgb(100, 108, 127);
    line-height: 1.4rem;
    font-family: Roboto, "Open Sans", sans-serif;
    font-size: 20px;
    font-weight: 300;
    text-rendering: optimizeLegibility;
}

h1 { text-align: center; }

.dropzone{
    background:#fff;
    border:2px dashed #0d6efd;
    border-radius:8px;
    min-height:220px;
    padding:20px;
    transition:.3s;
}

.dropzone:hover{
    border-color:#198754;
}

.dropzone .dz-message{
    font-size:18px;
    color:#666;
}

.dropzone .dz-preview .dz-image{
    width:120px;
    height:120px;
    border-radius:8px;
    overflow:hidden;
}

.dropzone .dz-preview .dz-image img {
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
}

.dropzone .dz-preview{
    margin:10px;
}
/* Hide default "Remove file" text */
.dropzone .dz-remove {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 24px;
    height: 24px;
    background: #dc3545;
    color: #fff !important;
    border-radius: 50%;
    text-indent: -9999px; /* Hide text */
    overflow: hidden;
    cursor: pointer;
    z-index: 1000;
    text-decoration: none !important;
    box-shadow: 0 2px 6px rgba(0,0,0,.2);
}

/* Create X icon */
.dropzone .dz-remove::before {
    content: "✕";
    position: absolute;
    inset: 0;
    text-indent: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
    color: #fff;
}

/* Hover effect */
.dropzone .dz-remove:hover {
    background: #b02a37;
    transform: scale(1.1);
}
</style>

</head>
