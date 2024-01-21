<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/db/mysql33.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/middleware/ability.php";
class summary
{
    private $mysql_conn;
    private $userRole;

    public function __construct()
    {
        global $mysql_conn;
        $this->mysql_conn = $mysql_conn;
        $this->userRole = $_SESSION['login']['role'];
    }

    public function getTransaction()
    {
        $query = "SELECT SUM(p.HargaBeli) AS modal, SUM(pj.HargaJual) AS revenue, COUNT(p.IdPembelian) AS beli, COUNT(pj.IdPenjualan) AS jual 
        FROM barang b LEFT JOIN pembelian p ON b.IdBarang = p.IdBarang LEFT JOIN penjualan pj ON b.IdBarang = pj.IdBarang";

        $result = $this->mysql_conn->query($query);

        return $result->fetch_assoc();
    }

    public function bestEmploye()
    {
        $query = "SELECT pg.NamaPengguna, SUM(p.HargaJual) AS hasil, ROUND(SUM(p.HargaJual) / (SELECT SUM(HargaJual) FROM penjualan) * 100) AS Persen FROM penjualan p INNER JOIN pengguna pg ON p.IdPengguna = pg.IdPengguna
        GROUP BY pg.IdPengguna ORDER BY SUM(p.HargaJual) DESC";

        $result = $this->mysql_conn->query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function bestProduct()
    {
        $query = "SELECT b.NamaBarang, SUM(pj.JumlahPenjualan) AS terjual, ROUND(SUM(pj.JumlahPenjualan) / (SELECT SUM(JumlahPenjualan) FROM penjualan) * 100) AS persen FROM barang b LEFT JOIN penjualan pj ON pj.IdBarang = b.IdBarang
        GROUP BY b.IdBarang";

        $result = $this->mysql_conn->query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function calculateProfitLoss()
    {
        $query = "SELECT b.IdBarang, b.NamaBarang, 
              SUM(pj.HargaJual * pj.JumlahPenjualan) AS totalPenjualan, 
              SUM(p.HargaBeli * p.JumlahPembelian) AS totalPembelian
              FROM barang b
              LEFT JOIN penjualan pj ON b.IdBarang = pj.IdBarang
              LEFT JOIN pembelian p ON b.IdBarang = p.IdBarang
              GROUP BY b.IdBarang";

        $result = $this->mysql_conn->query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }
}
