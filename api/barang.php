<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/db/mysql33.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/middleware/ability.php";
class barang
{
    private $mysql_conn;
    private $userRole;

    public function __construct()
    {
        global $mysql_conn;
        $this->mysql_conn = $mysql_conn;
        $this->userRole = $_SESSION['login']['role'];
    }

    public function getAll()
    {
        $query = "SELECT b.IdBarang, b.NamaBarang, b.Keterangan, b.Satuan, (SUM(p.JumlahPembelian)-SUM(pj.JumlahPenjualan)) AS stok 
        FROM barang b LEFT JOIN pembelian p ON b.IdBarang = p.IdBarang LEFT JOIN penjualan pj ON b.IdBarang = pj.IdBarang 
        GROUP BY b.IdBarang ";

        $result = $this->mysql_conn->query($query);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT b.IdBarang, b.NamaBarang AS nama, b.Keterangan AS keterangan, b.Satuan AS satuan, (SUM(p.JumlahPembelian)-SUM(pj.JumlahPenjualan)) AS stok 
        FROM barang b LEFT JOIN pembelian p ON b.IdBarang = p.IdBarang LEFT JOIN penjualan pj ON b.IdBarang = pj.IdBarang
        WHERE b.IdBarang = $id
        GROUP BY b.IdBarang ";

        $result = $this->mysql_conn->query($query);

        return $result->fetch_assoc();
    }

    public function create($data)
    {
        if (ability::hasAccess($this->userRole, 'createBarang')) {
        } else {
            return false;
        }

        $nama = $this->mysql_conn->real_escape_string($data['nama']);
        $keterangan = $this->mysql_conn->real_escape_string($data['keterangan']);
        $satuan = $this->mysql_conn->real_escape_string($data['satuan']);

        $query = "INSERT INTO barang (NamaBarang, Keterangan, Satuan) VALUES ('$nama', '$keterangan', '$satuan')";
        $result = $this->mysql_conn->query($query);

        return ($result);
    }

    public function update($id, $data)
    {
        $nama = $this->mysql_conn->real_escape_string($data['nama']);
        $keterangan = $this->mysql_conn->real_escape_string($data['keterangan']);
        $satuan = $this->mysql_conn->real_escape_string($data['satuan']);
        $query = "UPDATE barang SET NamaBarang = '$nama', Keterangan = '$keterangan', Satuan = '$satuan' WHERE IdBarang = $id";
        $result = $this->mysql_conn->query($query);

        return ($result);
    }

    public function calculateTotalTransactions()
    {
        $query = "SELECT IdBarang,
                  SUM(JumlahPembelian) AS totalPembelian,
                  SUM(0) AS totalPenjualan
              FROM pembelian
              GROUP BY IdBarang
              
              UNION
              
              SELECT IdBarang,
                  SUM(0) AS totalPembelian,
                  SUM(JumlahPenjualan) AS totalPenjualan
              FROM penjualan
              GROUP BY IdBarang";

        $result = $this->mysql_conn->query($query);

        if (!$result) {
            die("Error executing query: " . $this->mysql_conn->error);
        }

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function getNamaBarangById($barangId)
    {
        $query = "SELECT NamaBarang FROM barang WHERE IdBarang = ?";

        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $barangId);

        $stmt->execute();
        $stmt->bind_result($namaBarang);
        $stmt->fetch();
        $stmt->close();

        return $namaBarang;
    }

    public function generateCombine()
    {
        // Mendapatkan total pembelian dan penjualan setiap barang
        $totalTransactions = $this->calculateTotalTransactions();

        // Struktur data untuk menyimpan hasil kombinasi paket
        $combinedPackages = [];

        // Jumlah barang yang akan dijual dalam setiap paket
        $jumlahBarangPerPaket = 3;

        // Mengurutkan barang berdasarkan keuntungan per barang (dari tinggi ke rendah)
        usort($totalTransactions, function ($a, $b) {
            return $b['totalPenjualan'] - $b['totalPembelian'] - ($a['totalPenjualan'] - $a['totalPembelian']);
        });

        // Iterasi untuk membuat kombinasi paket
        $numBarang = count($totalTransactions);
        for ($i = 0; $i < $numBarang; $i += $jumlahBarangPerPaket) {
            // Membuat paket baru
            $arrayBarang = [];
            for ($j = 0; $j < $jumlahBarangPerPaket && ($i + $j) < $numBarang; $j++) {
                $arrayBarang[] = $totalTransactions[$i + $j]['IdBarang'];
            }

            // Menambahkan paket ke hasil kombinasi
            $combinedPackages[] = [
                'namaPaket' => 'Paket ' . ($i / $jumlahBarangPerPaket + 1),
                'arrayBarang' => $arrayBarang,
            ];
        }

        return $combinedPackages;
    }
}
