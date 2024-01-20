<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/api/barang.php";
$barang = new barang('kasir');
$barangData = $barang->getAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] == 'getById' && isset($_POST['userId'])) {
        $dataOne = $user->getById($_POST['userId']);
        if ($dataOne) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => true, "data" => $dataOne));
            exit();
        } else {
            http_response_code(500);
            exit();
        }
    } else if (isset($_POST['userId'])) {
        $result = $user->delete($_POST['userId']);
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
                                                    <a href="#" class="btn btn-primary btn-sm edit-user-btn" data-toggle="modal" data-target="#editUserModal" data-userid="<?= $data['IdPengguna'] ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal" data-userid="<?= $data['IdPengguna'] ?>">
                                                        <i class="fas fa-trash"></i>
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

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus pengguna ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a id="deleteButton" href="" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form Edit User -->
                    <form action="submit.php" method="POST" enctype="multipart/form-data">
                        <!-- Hidden input untuk mengirimkan ID user -->
                        <input type="hidden" name="userId" value="">

                        <!-- Field Username -->
                        <div class="form-group">
                            <label for="editUsername">Username</label>
                            <input type="text" class="form-control" id="editUsername" name="editUsername" value="">
                        </div>

                        <!-- Field First Name -->
                        <div class="form-group">
                            <label for="editFirstName">First Name</label>
                            <input type="text" class="form-control" id="editFirstName" name="editFirstName" value="">
                        </div>

                        <!-- Field Last Name -->
                        <div class="form-group">
                            <label for="editLastName">Last Name</label>
                            <input type="text" class="form-control" id="editLastName" name="editLastName" value="">
                        </div>

                        <!-- Field Phone -->
                        <div class="form-group">
                            <label for="editPhone">Phone</label>
                            <input type="text" class="form-control" id="editPhone" name="editPhone" value="">
                        </div>

                        <!-- Field Address -->
                        <div class="form-group">
                            <label for="editAddress">Address</label>
                            <textarea class="form-control" id="editAddress" name="editAddress"></textarea>
                        </div>

                        <!-- Field Role -->
                        <div class="form-group">
                            <label for="editRole">Role</label>
                            <select class="form-control" id="editRole" name="editRole">
                                <?php
                                $role = new role('kasir');
                                $roleData = $role->getAll();
                                foreach ($roleData as $row) {
                                ?>
                                    <option value="<?= $row['IdHakAkses'] ?>"><?= $row['NamaAkses'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Tombol Simpan -->
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SUKSES EDIT -->
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

    <!-- Modal Tambah User -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="submit.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="new" value="yes">
                        <div class="form-group">
                            <label for="addUsername">Username</label>
                            <input type="text" class="form-control" id="addUsername" name="addUsername" required>
                        </div>

                        <div class="form-group">
                            <label for="addPassword">Password</label>
                            <input type="password" class="form-control" id="addPassword" name="addPassword" required>
                        </div>

                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                            <span id="passwordMismatchMessage" class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="addFirstName">First Name</label>
                            <input type="text" class="form-control" id="addFirstName" name="addFirstName" required>
                        </div>

                        <div class="form-group">
                            <label for="addLastName">Last Name</label>
                            <input type="text" class="form-control" id="addLastName" name="addLastName" required>
                        </div>

                        <div class="form-group">
                            <label for="addPhone">Phone</label>
                            <input type="text" class="form-control" id="addPhone" name="addPhone" required>
                        </div>

                        <div class="form-group">
                            <label for="addAddress">Address</label>
                            <textarea class="form-control" id="addAddress" name="addAddress" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="addRole">Role</label>
                            <select class="form-control" id="addRole" name="addRole" required>
                                <?php
                                $role = new role('kasir');
                                $roleData = $role->getAll();
                                foreach ($roleData as $row) {
                                ?>
                                    <option value="<?= $row['IdHakAkses'] ?>"><?= $row['NamaAkses'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
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
            var userId = $(this).data('userid');

            // Menggunakan AJAX untuk memanggil API getById dengan metode POST
            $.ajax({
                type: "POST",
                url: "index.php",
                data: {
                    action: 'getById',
                    userId: userId
                },
                success: function(response) {
                    if (response.success) {
                        // Mengatur nilai input di dalam modal berdasarkan response
                        var user = response.user;
                        $('#editUserModal').find('#editUsername').val(user.username);
                        $('#editUserModal').find('#editFirstName').val(user.firstName);
                        $('#editUserModal').find('#editLastName').val(user.lastName);
                        $('#editUserModal').find('#editRole').val(user.idAkses);
                        $('#editUserModal').find('#editPhone').val(user.phone);
                        $('#editUserModal').find('#editAddress').val(user.address);
                        $('#editUserModal').find('input[name="userId"]').val(userId);
                    } else {
                        console.error("Failed to fetch user data");
                    }
                },
                error: function() {
                    console.error("Failed to communicate with the server");
                }
            });
        });

        $(document).ready(function() {
            $('#confirmDeleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Tombol yang memicu modal
                var userId = button.data('userid'); // Mendapatkan data-userid dari tombol

                // Menangani kejadian saat tombol Hapus diklik
                $('#deleteButton').on('click', function(event) {
                    event.preventDefault();
                    // Konfirmasi apakah pengguna yakin ingin menghapus
                    // var confirmDelete = confirm("Are you sure you want to delete this user?");
                    // console.log(userId)


                    // Menggunakan AJAX untuk memanggil API delete dengan metode POST
                    $.ajax({
                        type: "POST",
                        url: "./index.php",
                        data: {
                            userId: userId
                        }, // Ubah 'id' menjadi 'userId'
                        success: function(response) {
                            if (response) {
                                // Hapus baris atau lakukan aksi lain jika berhasil
                                console.log("User deleted successfully");
                                location.reload();
                            } else {
                                console.error("Failed to delete user");
                            }
                        },
                        error: function() {
                            console.error("Failed to communicate with the server");
                        }
                    });

                });
            });
        });

        $(document).ready(function() {
            // Menambahkan event listener pada input password dan confirm password
            $('#addPassword, #confirmPassword').on('input', function() {
                var password = $('#addPassword').val();
                var confirmPassword = $('#confirmPassword').val();
                var passwordMismatchMessage = $('#passwordMismatchMessage');

                // Memeriksa apakah password dan confirm password cocok
                if (password !== confirmPassword) {
                    passwordMismatchMessage.html('Password dan confirm password tidak cocok');
                    $('#submitBtn').prop('disabled', true); // Menonaktifkan tombol submit
                } else {
                    passwordMismatchMessage.html(''); // Menghapus pesan error jika cocok
                    $('#submitBtn').prop('disabled', false); // Mengaktifkan tombol submit
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