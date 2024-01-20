<?php
session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/db/mysql33.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/middleware/ability.php";
class role{
    private $mysql_conn;
    private $userRole = $_SESSION['login']['role'];

    public function __construct()
    {
        global $mysql_conn;
        $this->mysql_conn = $mysql_conn;
    }

    public function getAll(){
        // if(ability::hasAccess($this->userRole, 'getBarang')){

        // }else{
        //     return false;
        // }
        $query = "SELECT * FROM hakakses";

        $result = $this->mysql_conn->query($query);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id){
        $query = "SELECT b.IdBarang, b.NamaBarang, b.Keterangan, b.Satuan, (SUM(p.JumlahPembelian)-SUM(pj.JumlahPenjualan)) AS stok 
        FROM barang b LEFT JOIN pembelian p ON b.IdBarang = p.IdBarang LEFT JOIN penjualan pj ON b.IdBarang = pj.IdBarang
        WHERE b.IdBarang = $id
        GROUP BY b.IdBarang ";

        $result = $this->mysql_conn->query($query);

        return $result->fetch_assoc();
    }

    public function create($data){
        if(ability::hasAccess($this->userRole, 'createBarang')){

        }else{
            return false;
        }

        $nama = $this->mysql_conn->real_escape_string($data['NamaBarang']);
        $keterangan = $this->mysql_conn->real_escape_string($data['Keterangan']);
        $satuan = $this->mysql_conn->real_escape_string($data['Satuan']);

        $query = "INSERT INTO barang (NamaBarang, Keterangan, Satuan) VALUES ('$nama', '$keterangan', '$satuan')";
        $result = $this->mysql_conn->query($query);

        return($result);
    }

    public function update($id, $data){
        $nama = $this->mysql_conn->real_escape_string($data['NamaBarang']);
        $keterangan = $this->mysql_conn->real_escape_string($data['Keterangan']);
        $satuan = $this->mysql_conn->real_escape_string($data['Satuan']);
        $query = "UPDATE barang SET NamaBarang = '$nama', Keterangan = '$keterangan', Satuan = '$satuan' WHERE IdBarang = $id";
        $result = $this->mysql_conn->query($query);

        return($result);
    }
}
?>