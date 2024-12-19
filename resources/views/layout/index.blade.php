<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
        <meta name="description" content="Spruha -  Admin Panel HTML Dashboard assets">
        <meta name="author" content="Spruko Technologies Private Limited">
        <meta name="keywords"
            content="admin,dashboard,panel,bootstrap admin assets,bootstrap dashboard,dashboard,themeforest admin dashboard,themeforest admin,themeforest dashboard,themeforest admin panel,themeforest admin assets,themeforest admin dashboard,cool admin,it dashboard,admin design,dash assetss,saas dashboard,dmin ui design">

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('assets') }}/img/brand/favicon.ico" type="image/x-icon" />

        <!-- SweetAlert CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

        <!-- SweetAlert JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

        <!-- Title -->
        <title>User | @yield('title')</title>
        <!-- Title -->
        <title>User | @yield('title')</title>

        <!-- Bootstrap css-->
        <link id="style" href="{{ asset('assets') }}/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

        <!-- Icons css-->
        <link href="{{ asset('assets') }}/plugins/web-fonts/icons.css" rel="stylesheet" />
        <link href="{{ asset('assets') }}/plugins/web-fonts/font-awesome/font-awesome.min.css" rel="stylesheet">
        <link href="{{ asset('assets') }}/plugins/web-fonts/plugin.css" rel="stylesheet" />

        <!-- Style css-->
        <link href="{{ asset('assets') }}/css/style.css" rel="stylesheet">

        <!-- Select2 css -->
        <link href="{{ asset('assets') }}/plugins/select2/css/select2.min.css" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('assets/plugins/datatable/datatables.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatable/css/buttons.dataTables.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatable/responsive.dataTables.min.css') }}">

        <link rel="stylesheet" href="{{ asset('assets/plugins/docx-viewer/dist/thumbnail.css') }}">

        <!-- Datepicker -->
        <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.css') }}" rel="stylesheet" />

        <!-- Daterange Picker -->
        <link href="{{ asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />

        <!-- Summernote -->
        <link rel="stylesheet" href="{{asset('assets/plugins/summernote/summernote-bs5.min.css')}}">

        <!-- quill -->
        <link href="{{ asset('assets/plugins/quill/quill.snow.css') }}" rel="stylesheet">

        <style>
        body.dt-print-view h1 {
            text-align: center;
            margin-top: 1em;
        }

        body.dt-print-view div {
            text-align: center;
            font-size: 1.5em;
            margin-bottom: 1em;
        }
        </style>

    </head>

    <body class="ltr main-body leftmenu">

        <!-- Loader -->
        <div id="global-loader">
            <img src="{{ asset('assets') }}/img/loader.svg" class="loader-img" alt="Loader">
        </div>
        <!-- End Loader -->

        <!-- Page -->
        <div class="page">

            <!-- Main Header-->
            <div class="sticky main-header side-header">
                <div class="main-container container-fluid">
                    <div class="main-header-left">
                        <a class="main-header-menu-icon" href="javascript:void(0)"
                            id="mainSidebarToggle"><span></span></a>
                        <div class="hor-logo">
                            <a class="main-logo" href="index.html">
                                <img src="{{ asset('assets') }}/img/brand/logo.png"
                                    class="header-brand-img desktop-logo" alt="logo">
                                <img src="{{ asset('assets') }}/img/brand/logo-light.png"
                                    class="header-brand-img desktop-logo-dark" alt="logo">
                            </a>
                        </div>
                    </div>
                    <div class="main-header-center">
                        <div class="responsive-logo">
                            <a href="index.html"><img src="{{ asset('assets') }}/img/brand/logo.png" class="mobile-logo"
                                    alt="logo"></a>
                            <a href="index.html"><img src="{{ asset('assets') }}/img/brand/logo-light.png"
                                    class="mobile-logo-dark" alt="logo"></a>
                        </div>
                    </div>
                    <div class="main-header-right">
                        <button class="navbar-toggler navresponsive-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fe fe-more-vertical header-icons navbar-toggler-icon"></i>
                        </button><!-- Navresponsive closed -->

                        <div
                            class="navbar navbar-expand-lg nav nav-item navbar-nav-right responsive-navbar navbar-dark ">
                            <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                <div class="d-flex order-lg-2 ms-auto">
                                    <!-- Theme-Layout -->
                                    <div class="dropdown d-flex main-header-theme">
                                        <a class="nav-link icon layout-setting">
                                            <span class="dark-layout">
                                                <i class="fe fe-sun header-icons"></i>
                                            </span>
                                            <span class="light-layout">
                                                <i class="fe fe-moon header-icons"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <!-- Theme-Layout -->
                                    <!-- country -->
                                    <!-- country -->
                                    <!-- Full screen -->
                                    <!-- Full screen -->
                                    <!-- Notification -->
                                    <!-- Profile -->
                                    <div class="dropdown main-profile-menu">
                                        <a class="d-flex" href="#">
                                            <span class="main-img-user"><img alt="avatar"
                                                    src="{{ Storage::url(auth()->user()->photo_path) }}"></span>
                                        </a>
                                        <div class="dropdown-menu">
                                            <div class="header-navheading">
                                                <h6 class="main-notification-title">{{ auth()->user()->name }}</h6>
                                                <p class="main-notification-text">{{ auth()->user()->role }}</p>
                                            </div>

                                            <a class="dropdown-item" href="/profile/edit">
                                                <i class="fe fe-edit"></i> Edit Profile
                                            </a>
                                            <a class="dropdown-item" href="profile.html">
                                                <i class="fe fe-settings"></i> Account Settings
                                            </a>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <a class="dropdown-item" href="/logout">
                                                    <button type="submit" class="btn ripple btn-main-primary"><i
                                                            class="fe fe-power"></i> Sign Out</button>
                                                </a>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- Profile -->
                                    <!-- Sidebar -->
                                    <!-- Sidebar -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Main Header-->

            <!-- Sidemenu -->
            @include('layout.sidebar')
            <!-- End Sidemenu -->

            <!-- Main Content-->
            @yield('content')

            <!-- End Main Content-->

            <!-- Main Footer-->
            @include('layout.footer')
            <!--End Footer-->

        </div>
        <!-- End Page -->

        <!-- Back-to-top -->
        <a href="#top" id="back-to-top"><i class="fe fe-arrow-up"></i></a>

        <!-- Jquery js-->
        <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>

        <!-- Bootstrap js-->
        <script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <!-- Perfect-scrollbar js -->
        <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

        <!-- Sidemenu js -->
        <script src="{{ asset('assets/plugins/sidemenu/sidemenu.js') }}" id="leftmenu"></script>

        <!-- Sidebar js -->
        <script src="{{ asset('assets/plugins/sidebar/sidebar.js') }}" id="leftMenu"></script>

        <!-- Select2 js-->
        <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
        <script src="{{ asset('assets/js/select2.js') }}"></script>

        <!-- Datatables -->
        <script src="{{ asset('assets/plugins/datatable/datatables.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatable/responsive.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatable/js/buttons.bootstrap5.min.js') }}"></script>

        <script crossorigin src="https://unpkg.com/jszip/dist/jszip.min.js"></script>
        <script crossorigin src="https://unpkg.com/tiff.js@1.0.0/tiff.min.js"></script>

        <!-- Docx Viewer -->
        <script src="{{ asset('assets/plugins/docx-viewer/dist/docx-preview.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/docx-viewer/dist/thumbnail.js') }}"></script>
        <script src="{{ asset('assets/plugins/docx-viewer/dist/tiff-preprocessor.js') }}"></script>

        <!-- DatePicker -->
        <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>

        <!-- Daterange Picker -->
        <script src="{{ asset('assets/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

        <!-- Chart.js -->
        <script src="{{ asset('assets/plugins/chart.js/chart.umd.js') }}"></script>

        <!-- summernote -->
        <script src="{{asset('assets/plugins/summernote/summernote-bs5.min.js')}}"></script>

        <!-- quill -->
        <script src="{{ asset('assets/plugins/quill/quill.js') }}"></script>

        <!-- Color Theme js -->
        <script src="{{ asset('assets/js/themeColors.js') }}"></script>

        <!-- Sticky js -->
        <script src="{{ asset('assets/js/sticky.js') }}"></script>

        <!-- Custom js -->
        <script src="{{ asset('assets/js/custom.js') }}"></script>

        <script>
        $(document).ready(function() {
            //datepicker
            $('.datePickers').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });

            //summernote
            $('.summernote').summernote({
                tabsize: 2,
                height: 100
            })

            //quill
            var quill = new Quill('#quill', {
                theme: 'snow'
            });

            //daterangepicker
            $('.daterange').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                },
                autoUpdateInput: false,
                open: 'right',
                drop: 'down',
                autoApply: true,

            });
        })
        </script>

        @stack('scripts')

    </body>

</html>