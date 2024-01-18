<?php
// header('Content-Type: application/json');
include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/api/user.php";
$user = new user('kasir');
$userData = $user->getAll();

// $reqData = json_decode(file_get_contents("php://input"), true);

// print_r($reqData);
// exit();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if($_POST['action'] == 'getById' && isset($_POST['userId'])){
        $userOne = $user->getById($_POST['userId']);
        if ($userOne) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => true, "user" => $userOne));
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

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/components/sidebar.php";
        ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php
                include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/components/navbar.php";
                ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">User</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <!-- <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
                        </div> -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Role</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($userData as $data) {
                                        ?>
                                            <tr>
                                                <td><?= $data['username'] ?></td>
                                                <td><?= $data['firstName'] ?></td>
                                                <td><?= $data['lastName'] ?></td>
                                                <td><?= $data['role'] ?></td>
                                                <td><?= $data['phone'] ?></td>
                                                <td><?= $data['address'] ?></td>
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
                <!-- /.container-fluid -->


            </div>
            <!-- End of Main Content -->

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
                    <form action="edit_user.php" method="POST">
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
                                <!-- Pilihan role, Anda dapat mengambil data ini dari database -->
                                <!--  -->
                                <!-- ... tambahkan pilihan role lainnya ... -->
                            </select>
                        </div>

                        <!-- Tombol Simpan -->
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
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
                        $('#editUserModal').find('#editRole').val(user.role);
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
    </script>

</body>

</html>