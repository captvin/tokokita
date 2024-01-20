<?php
session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/db/mysql33.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/middleware/ability.php";
class penjualan{
    private $mysql_conn;
    private $userRole = $_SESSION['login']['role'];

    public function __construct()
    {
        global $mysql_conn;
        $this->mysql_conn = $mysql_conn;
    }

    public function getAll(){
        if(ability::hasAccess($this->userRole, 'getJual')){

        }else{
            return false;
        }
        
        $query = "SELECT * FROM penjualan";

        $result = $this->mysql_conn->query($query);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // public function getById($id){
    //     $query = "SELECT b.IdBarang, b.NamaBarang AS nama, b.Keterangan AS keterangan, b.Satuan AS satuan, (SUM(p.JumlahPembelian)-SUM(pj.JumlahPenjualan)) AS stok 
    //     FROM barang b LEFT JOIN pembelian p ON b.IdBarang = p.IdBarang LEFT JOIN penjualan pj ON b.IdBarang = pj.IdBarang
    //     WHERE b.IdBarang = $id
    //     GROUP BY b.IdBarang ";

    //     $result = $this->mysql_conn->query($query);

    //     return $result->fetch_assoc();
    // }

    public function create($data){
        if(ability::hasAccess($this->userRole, 'createJual')){

        }else{
            return false;
        }

        $jumlah = $data['jumlah'];
        $harga = $data['harga'];
        $pelanggan = $data['pelanggan'];
        $barang = $data['barang'];
        $pengguna = $_SESSION['login']['user_id'];

        $query = "INSERT INTO penjualan (JumlahPenjualan, HargaJual, IdPengguna, IdBarang, id_pelanggan) VALUES ($jumlah, $harga, $pengguna, $barang, $pelanggan)";
        $result = $this->mysql_conn->query($query);

        return($result);
    }

    // public function update($id, $data){
    //     $nama = $this->mysql_conn->real_escape_string($data['nama']);
    //     $keterangan = $this->mysql_conn->real_escape_string($data['keterangan']);
    //     $satuan = $this->mysql_conn->real_escape_string($data['satuan']);
    //     $query = "UPDATE barang SET NamaBarang = '$nama', Keterangan = '$keterangan', Satuan = '$satuan' WHERE IdBarang = $id";
    //     $result = $this->mysql_conn->query($query);

    //     return($result);
    // }
}
?>