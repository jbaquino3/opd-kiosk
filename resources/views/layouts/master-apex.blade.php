<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>OPD eKiosk - APEX</title>

    <link rel="icon" href="{{url('logo/bghmc.png')}}">
    <!--scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div id="app">
        <div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <div class="nav-link text-success">OPD eRegistration Kiosk - APEX</div>
                    </li>
                </ul>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <!-- Messages Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="/"><i class="fa fa-arrow-left"></i> &nbsp; Back
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-light-primary elevation-4">
                <!-- Brand Logo -->
                <a href="#" class="brand-link">
                    <img src="{{ asset('images/bghlogogif.gif') }}" class="brand-image img-circle elevation-3"
                        style="opacity: .8">
                    <span class="brand-text font-weight-light">BGHMC OPD Kiosk</span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="info">
                            <a href="#" class="d-block">Out-Patient Department</a>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <!-- Add icons to the links using the .nav-icon class
                                with font-awesome or any other icon font library -->
                            <li class="nav-item has-treeview menu-open">
                                <router-link to="/im" class="nav-link">
                                    <i class="nav-icon fas fa-hospital text-blue"></i>
                                    <p>
                                        Internal Medicine
                                    </p>
                                </router-link>
                                <router-link to="/pedia" class="nav-link">
                                    <i class="nav-icon fas fa-child text-orange"></i>
                                    <p>
                                        Pediatrics
                                    </p>
                                </router-link>
                                <router-link to="/obgyne" class="nav-link">
                                    <i class="nav-icon fas fa-stethoscope text-red"></i>
                                    <p>
                                        Obstetrics-Gynecology
                                    </p>
                                </router-link>
                                <router-link to="/surgery" class="nav-link">
                                    <i class="nav-icon fas fa-hospital text-green"></i>
                                    <p>
                                        Surgery
                                    </p>
                                </router-link>
                                <router-link to="/ophtha" class="nav-link">
                                    <i class="nav-icon fas fa-eye text-blue"></i>
                                    <p>
                                        Ophthalmology
                                    </p>
                                </router-link>
                                <router-link to="/ortho" class="nav-link">
                                    <i class="nav-icon fas fa-bone text-orange"></i>
                                    <p>
                                        Orthopedics
                                    </p>
                                </router-link>
                                <router-link to="/ent" class="nav-link">
                                    <i class="nav-icon fas fa-user text-red"></i>
                                    <p>
                                        Ears, Nose, Throat
                                    </p>
                                </router-link>
                                <router-link to="/psych" class="nav-link">
                                    <i class="nav-icon fas fa-brain text-green"></i>
                                    <p>
                                        Psychiatry
                                    </p>
                                </router-link>
                                <router-link to="/cancer_center" class="nav-link">
                                    <i class="nav-icon fas fa-clinic-medical text-cyan"></i>
                                    <p>
                                        Cancer Center
                                    </p>
                                </router-link>
                                <router-link to="/others" class="nav-link">
                                    <i class="nav-icon fas fa-book-medical text-red"></i>
                                    <p>
                                        Others
                                    </p>
                                </router-link>
                            </li>
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
                    <router-view></router-view>
                </section>
                <!-- /.content -->
            </div>

            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <strong>Developed by the BGHMC MIS Department</strong>
                All rights reserved. 2020
            </footer>

        </div>
    </div>
</body>
</html>
