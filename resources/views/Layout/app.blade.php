<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Zameedar</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('backend/assets/images/Zameedar-favicon.ico')}}" />
    
    <!-- Custom CSS -->
    <link href="{{asset('backend/assets/libs/flot/css/float-chart.css')}}" rel="stylesheet" />
    <link href="{{asset('backend/dist/css/style.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('backend/dist/css/custom2.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/libs/select2/dist/css/select2.min.css') }}" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <style>
        .error {
            color: red;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
    </style>

</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <!-- Main Wrapper -->
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

        <!-- Include Header and Sidebar -->
        @include('Layout.header')
        @include('Layout.sidebar')

        <div class="page-wrapper">
            @yield('content')
            @include('Layout.footer')
        </div>

    </div>

    <!-- Include Footer Scripts -->
    @include('Layout.script')

    <!-- DataTable JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}


    <!-- DataTable Initialization Script -->
    @stack('scripts')
</body>

</html>
