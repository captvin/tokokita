<?php
session_start();
if (empty($_SESSION['login'])) {
    header("Location: ../login/index.php");
    exit();
}

// var_dump($_SESSION['login']['role']);
// exit();

include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/api/summary.php";
$summary = new summary('kasir');
$transactionData = $summary->getTransaction();
$employe = $summary->bestEmploye();
$product = $summary->bestProduct();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="../../vendors/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<script>
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
</script>

<script>
    var product = <?php echo json_encode($product); ?>;
    var productColors = Array.from({
        length: product.length
    }, () => getRandomColor());
</script>

<body id="page-top">
    <div id="wrapper">
        <?php
        include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/components/sidebar.php";
        ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <?php
                include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/components/navbar.php";
                ?>
                <!-- Begin age content kasir -->
                <div class="container-fluid <?= $_SESSION['login']['role'] == 'Kasir' ? '' : 'd-none' ?>">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <div class="row d-flex justify-content-center align-items-center ">
                        <div class="d-block justify-content-center align-items-center">
                            
                            <div>
                            <h1 id="realtimeClock"></h1>
                            </div>
                            
                            <div>
                            <button class="btn btn-primary">Penjualan</button>
                            <button class="btn btn-success">Pembelian</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Begin Page Content Admin dan Manager -->
                <div class="container-fluid <?= $_SESSION['login']['role']  == 'Kasir' ? 'd-none' : '' ?>">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Modal</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo 'Rp.' . number_format($transactionData['modal'], 2); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Income (Kotor)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo 'Rp.' . number_format($transactionData['revenue'], 2); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Pembelian</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $transactionData['beli'] ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Penjualan</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $transactionData['jual'] ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-5">
                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Best Employe Sales</h6>
                                </div>
                                <div class="card-body">

                                    <?php
                                    foreach ($employe as $data) {
                                        switch (true) {
                                            case ($data['Persen'] >= 75):
                                                $progressBarColor = 'bg-success';
                                                break;
                                            case ($data['Persen'] >= 50):
                                                $progressBarColor = 'bg-info';
                                                break;
                                            case ($data['Persen'] >= 25):
                                                $progressBarColor = 'bg-warning';
                                                break;
                                            default:
                                                $progressBarColor = 'bg-danger';
                                                break;
                                        }
                                    ?>
                                        <h4 class="small font-weight-bold"><?= $data['NamaPengguna'] ?><span class="float-right"><?= $data['Persen'] ?>%</span></h4>
                                        <div class="progress mb-4">
                                            <div class="progress-bar <?= $progressBarColor ?>" role="progressbar" style="width: <?= $data['Persen'] ?>%" aria-valuenow="<?= $data['Persen']  ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Top Sales</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <?php
                                        $index = 0;
                                        foreach ($product as $data) {
                                        ?>
                                            <span class="mr-2">
                                                <i class="fas fa-circle" id="colorProduct[<?= $index ?>]"></i> <?= $data['NamaBarang'] ?>
                                            </span>
                                        <?php
                                            $index++;
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/components/footer.php";
            ?>

        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php
    include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/components/logoutModal.php";
    ?>

    <!-- Bootstrap core JavaScript-->
    <script src="../../vendors/jquery/jquery.min.js"></script>
    <script src="../../vendors/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendors/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../../vendors/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../../assets/js/demo/chart-area-demo.js"></script>

    <script>
        for (var i = 0; i < productColors.length; i++) {
            // var data = productColors[i];
            var titik = document.getElementById(`colorProduct[${i}]`);
            titik.style.color = productColors[i]
        }

        function padZero(number) {
            return number < 10 ? "0" + number : number;
        }

        function updateClock() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();

            // Format jam seperti "HH:mm:ss"
            var formattedTime = padZero(hours) + ":" + padZero(minutes) + ":" + padZero(seconds);

            // Tampilkan waktu pada elemen dengan id "realtime-clock"
            document.getElementById("realtimeClock").innerText = formattedTime;
        }

        setInterval(updateClock, 1000);
    </script>

    <script>
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        // Pie Chart Example
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: product.map(item => item.NamaBarang),
                datasets: [{
                    data: product.map(item => item.persen),
                    backgroundColor: productColors,
                    hoverBackgroundColor: productColors.map(color => adjustBrightness(color, -10)),
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });

        function adjustBrightness(hex, percent) {
            var r = parseInt(hex.slice(1, 3), 16);
            var g = parseInt(hex.slice(3, 5), 16);
            var b = parseInt(hex.slice(5, 7), 16);

            r = Math.round(r * (100 + percent) / 100);
            g = Math.round(g * (100 + percent) / 100);
            b = Math.round(b * (100 + percent) / 100);

            r = (r < 255) ? r : 255;
            g = (g < 255) ? g : 255;
            b = (b < 255) ? b : 255;

            var newColor = `rgb(${r},${g},${b})`;
            return newColor;
        }
    </script>

</body>

</html>