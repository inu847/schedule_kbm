<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="sofi" content="" />
        <title>Dashboard - SB Admin</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"> --}}
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark d-flex justify-content-between">
            <!-- Navbar Brand-->
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block text-light">
                <h4>APLIKASI PENJADWALAN SEKOLAH</h4>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="logo-mi">
                <img src="img/logo.png" class="w-50" alt="">
                </div>
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="/">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-line"></i></div>
                                Dashboard
                            </a>
                            <!-- data kelas -->
                            <a class="nav-link" href="/data-kelas">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
                                Data Kelas
                            </a>
                            <!-- data mapel -->
                            <div class="dropdown nav-link" href="/data-mapel">
                                <a class="dropdown-toggle text-secondary text-decoration-none" type="button" style="background: none; border: none;" data-bs-toggle="dropdown" 
                                aria-expanded="false" >
                                <i class="fa-solid fa-book-open-reader"></i> <span>Data Mapel</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <li><a class="dropdown-item active" href="/mapel-umum"class="text-decoration-none text-secondary">
                                        <i class="fa-solid fa-book-open-reader"></i> Mapel Umum</a></li>
                                    <li><a class="dropdown-item" href="/mapel-agama" class="text-decoration-none text-secondary">
                                        <i class="fa-solid fa-book-open-reader"></i> Mapel Agama</a></li>
                                </ul>
                            </div>
                            <!-- data guru -->
                            <a class="nav-link" href="/data-guru">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                                Data Guru
                            </a>
                            <!-- data ruangan -->
                            <a class="nav-link" href="/data-ruangan">
                                <div class="sb-nav-link-icon"><i class="fa-sharp fa-light fa-landmark"></i></div>
                                Data Ruangan
                            </a>
                            <!-- data waktu tidak tersedia -->
                            <a class="nav-link" href="/data-waktu">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-business-time"></i></div>
                                Waktu Tidak Tersedia
                            </a>
                            <!-- generate jadwal -->
                            <a class="nav-link" href="/generate-jadwal">
                                <div class="sb-nav-link-icon"><i class="fa-brands fa-searchengin"></i></div>
                                Generate Jadwal
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        @yield("content")
                    </div>
                </main>
                <footer class="py-2 bg-light mt-auto">
                    <div class="container-fluid px-2">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Penjadwalan MI Al-Ma'arif Margomulyo 02 2023</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        {{-- <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script> --}}
        {{-- <script src="https://code.jquery.com/jquery-3.1.0.js"></script> --}}
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('.tabel-data').DataTable();
            });
        </script>
    </body>
</html>
