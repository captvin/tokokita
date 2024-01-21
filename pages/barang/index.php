<?php
session_start();

if (empty($_SESSION['login']['user_id'])) {
    header("Location: ../login/index.php");
    exit();
} else if ($_SESSION['login']['role'] != 'Admin') {
    echo '<script src="../../vendors/jquery/jquery.min.js"></script>; <script>
        $(document).ready(function(){
            $("#roleModal").modal("show");
        });
    </script>';
}

if (isset($_POST['okButton'])) {
    header("Location: ../dashboard/index.php");
    exit();
}

include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/api/barang.php";
$barang = new barang();
$barangData = $barang->getAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] == 'getById' && isset($_POST['param'])) {
        $dataOne = $barang->getById($_POST['param']);
        if ($dataOne) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => true, "data" => $dataOne));
            exit();
        } else {
            http_response_code(500);
            exit();
        }
    } else if (isset($_POST['param'])) {
        $result = $user->delete($_POST['param']);
        if ($result) {
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(array("success" => true));
            exit();
        } else {
            http_response_code(500);
        }
        exit();
    }
}


?>



<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - User</title>

    <!-- Custom fonts for this template-->
    <link href="../../vendors/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

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

                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Barang</h1>
                        <a href="#" class="btn btn-success" data-toggle="modal" data-target="#addUserModal">
                            <i class="fas fa-user-plus"></i> Tambah Barang
                        </a>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Keterangan</th>
                                            <th>Satuan</th>
                                            <th>Stok</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($barangData as $row) {
                                        ?>
                                            <tr>
                                                <td><?= $row['NamaBarang'] ?></td>
                                                <td><?= $row['Keterangan'] ?></td>
                                                <td><?= $row['Satuan'] ?></td>
                                                <td><?= $row['stok'] ?></td>
                                                <td>
                                                    <a href="#" class="btn btn-primary btn-sm edit-user-btn" data-toggle="modal" data-target="#editUserModal" data-id="<?= $row['IdBarang'] ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/components/footer.php";
            ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php
    include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/components/role.php";
    ?>

    <!-- Modal Edit -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="submit.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">

                        <div class="form-group">
                            <label for="editNama">Nama</label>
                            <input type="text" class="form-control" id="editNama" name="editNama" value="">
                        </div>

                        <div class="form-group">
                            <label for="editKeterangan">Keterangan</label>
                            <input type="text" class="form-control" id="editKeterangan" name="editKeterangan" value="">
                        </div>

                        <div class="form-group">
                            <label for="editLastName">Satuan</label>
                            <input type="text" class="form-control" id="editSatuan" name="editSatuan" value="">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SUKSES -->
    <div class="modal fade" id="updateSuccessModal" tabindex="-1" role="dialog" aria-labelledby="updateSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateSuccessModalLabel">Update Success</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="pesan">
                    <!-- Data has been updated successfully. -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/components/logoutModal.php";
    ?>

    <!-- Modal Tambah -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="submit.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="new" value="yes">
                        <div class="form-group">
                            <label for="addnama">Nama</label>
                            <input type="text" class="form-control" id="addNama" name="addNama" required>
                        </div>

                        <div class="form-group">
                            <label for="addKeterangan">Keterangan</label>
                            <input type="text" class="form-control" id="addKeterangan" name="addKeterangan" required>
                        </div>

                        <div class="form-group">
                            <label for="addSatuan">Satuan</label>
                            <input type="text" class="form-control" id="addSatuan" name="addSatuan" required>
                        </div>

                        <!-- Tombol Simpan -->
                        <button type="submit" id="submitBtn" class="btn btn-primary">Tambah User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <!-- Bootstrap core JavaScript-->
    <script src="../../vendors/jquery/jquery.min.js"></script>
    <script src="../../vendors/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendors/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../../vendors/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendors/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../../assets/js/demo/datatables-demo.js"></script>

    <script>
        $('.edit-user-btn').on('click', function() {
            var Id = $(this).data('id');

            // Menggunakan AJAX untuk memanggil API getById dengan metode POST
            $.ajax({
                type: "POST",
                url: "index.php",
                data: {
                    action: 'getById',
                    param: Id
                },
                success: function(response) {
                    if (response.success) {
                        // Mengatur nilai input di dalam modal berdasarkan response
                        var data = response.data;
                        $('#editUserModal').find('#editNama').val(data.nama);
                        $('#editUserModal').find('#editKeterangan').val(data.keterangan);
                        $('#editUserModal').find('#editSatuan').val(data.satuan);
                        $('#editUserModal').find('input[name="id"]').val(Id);
                    } else {
                        console.error("Failed to fetch user data");
                    }
                },
                error: function() {
                    console.error("Failed to communicate with the server");
                }
            });
        });
    </script>

    <?php
    if (isset($_GET['success'])) {
        $successMessage;
        if ($_GET['success'] == 'edit') {
            $successMessage = "Data has been updated successfully";
        } else if ($_GET['success'] == 'add') {
            $successMessage = "New data added successfully";
        } else {
            $successMessage = "Failed";
        }

        echo '<script>
        $(document).ready(function(){
            $("#pesan").text("' . $successMessage . '");
            $("#updateSuccessModal").modal("show");
            var url = window.location.href;
            var newurl = url.split("?")[0];
            window.history.replaceState({}, document.title, newurl);
        });
    </script>';
    }
    ?>

</body>

</html>