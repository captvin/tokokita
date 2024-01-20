<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/db/mysql33.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/middleware/ability.php";
class user
{
    private $mysql_conn;
    private $userRole;

    public function __construct($userRole)
    {
        global $mysql_conn;
        $this->mysql_conn = $mysql_conn;
        $this->userRole = $userRole;
    }

    public function getAll()
    {
        $query = "SELECT p.IdPengguna, p.NamaPengguna AS username, p.NamaDepan AS firstName, p.NamaBelakang AS lastName, p.NoHp AS phone, p.Alamat AS address, h.NamaAkses as role 
        FROM pengguna p LEFT JOIN hakakses h ON p.IdAkses = h.IdHakAkses 
        ORDER BY p.NamaPengguna ";

        $result = $this->mysql_conn->query($query);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT p.IdPengguna, p.NamaPengguna AS username, p.NamaDepan AS firstName, p.NamaBelakang AS lastName, p.NoHp AS phone, p.Alamat AS address, h.NamaAkses AS role, h.IdHakAkses AS idAkses
        FROM pengguna p LEFT JOIN hakakses h ON p.IdAkses = h.IdHakAkses
        WHERE p.IdPengguna = $id
        ORDER BY p.NamaPengguna";

        $result = $this->mysql_conn->query($query);

        return $result->fetch_assoc();
    }

    public function create($data)
    {
        // if (ability::hasAccess($this->userRole, 'createBarang')) {
        // } else {
        //     return false;
        // }

        $nama = $this->mysql_conn->real_escape_string($data['username']);
        $password = $this->mysql_conn->real_escape_string($data['password']);
        $first = $this->mysql_conn->real_escape_string($data['first']);
        $last = $this->mysql_conn->real_escape_string($data['last']);
        $phone = $this->mysql_conn->real_escape_string($data['phone']);
        $address = $this->mysql_conn->real_escape_string($data['address']);
        $role = (int)$data['role'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $checkQuery = "SELECT COUNT(*) AS count FROM pengguna WHERE NamaPengguna = '$nama'";
        $checkResult = $this->mysql_conn->query($checkQuery);

        if ($checkResult) {
            $countRow = $checkResult->fetch_assoc();
            $count = $countRow['count'];

            if ($count > 0) {
                // NamaPengguna sudah ada, berhenti proses dan kembalikan pesan error
                return false;
            } else {
                // NamaPengguna belum ada, lanjutkan proses insert
                $query = "INSERT INTO pengguna (NamaPengguna, Password, NamaDepan, NamaBelakang, NoHp, Alamat, IdAkses) VALUES ('$nama', '$hashedPassword', '$first', '$last', '$phone', '$address', $role)";
                $result = $this->mysql_conn->query($query);

                return $result ? true : false;
            }
        } else {
            return false;
        }
    }

    public function update($id, $data)
    {
        $nama = $this->mysql_conn->real_escape_string($data['username']);
        $first = $this->mysql_conn->real_escape_string($data['first']);
        $last = $this->mysql_conn->real_escape_string($data['last']);
        $phone = $this->mysql_conn->real_escape_string($data['phone']);
        $address = $this->mysql_conn->real_escape_string($data['address']);
        $role = (int)$data['role'];

        $checkQuery = "SELECT COUNT(*) AS count FROM pengguna WHERE NamaPengguna = '$nama' AND idPengguna != $id";
        $checkResult = $this->mysql_conn->query($checkQuery);

        if ($checkResult) {
            $countRow = $checkResult->fetch_assoc();
            $count = $countRow['count'];

            if ($count > 0) {
                // NamaPengguna sudah ada, berhenti proses dan kembalikan pesan error
                return false;
            } else {
                // NamaPengguna belum ada, lanjutkan proses update
                $query = "UPDATE pengguna SET NamaPengguna = '$nama', NamaDepan = '$first', NamaBelakang = '$last', NoHp = '$phone', Alamat = '$address', IdAkses = $role WHERE idPengguna = $id";
                $result = $this->mysql_conn->query($query);

                return $result ? true : false;
            }
        } else {
            // Query untuk mengecek NamaPengguna gagal
            return false;
        }
    }

    public function changePassword($id, $old, $new)
    {
        $old = $this->mysql_conn->real_escape_string($old);
        $new = $this->mysql_conn->real_escape_string($new);

        // Ambil password lama dari database berdasarkan ID pengguna
        $query = "SELECT Password FROM pengguna WHERE IdPengguna = $id";
        $result = $this->mysql_conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $oldHash = $row['password'];

            // Periksa apakah password lama benar
            if (password_verify($old, $oldHash)) {
                // Password lama benar, hash password baru
                $newHash = password_hash($new, PASSWORD_DEFAULT);

                // Update password baru ke dalam database
                $updateQuery = "UPDATE pengguna SET Password = '$newHash' WHERE IdPenguuna = $id";
                $this->mysql_conn->query($updateQuery);

                return true; // Berhasil mengganti password
            } else {
                return false; // Password lama tidak cocok
            }
        } else {
            return false; // ID pengguna tidak ditemukan
        }
    }

    public function login($username, $password)
    {
        if (empty($username) || empty($password)) {
            return false; // Parameter tidak valid
        }
    
        // Menggunakan prepared statement untuk mencegah SQL Injection
        $stmt = $this->mysql_conn->prepare("SELECT * FROM pengguna p INNER JOIN hakakses h ON p.IdAkses = h.IdHakAkses WHERE NamaPengguna = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            print_r($userData);
    
            if (password_verify($password, $userData['Password'])) {
                // Set session jika login berhasil
                session_start();
                $_SESSION['login']['user_id'] = $userData['IdPengguna'];
                $_SESSION['login']['username'] = $userData['NamaPengguna'];
                $_SESSION['login']['role'] = $userData['NamaAkses'];
                return true; // Login berhasil
            }
        }
    
        return false; // Login gagal
    
    }

    public function delete($id)
    {
        $query = "DELETE FROM pengguna WHERE IdPengguna = $id";
        $result = $this->mysql_conn->query($query);

        return $result ? true : false;
    }
}
