<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/api/barang.php";
if (isset($_POST)) {

    $barang = new barang('admin');

    if (isset($_POST['new'])) {
        $data = array(
            'nama' => $_POST['addNama'],
            'keterangan' => $_POST['addKeterangan'],
            'satuan' => $_POST['addSatuan']
        );
        if ($barang->create($data)) {
            header("Location: index.php?success=add");
            exit();
        }
        else{
            header("Location: index.php?success=no");
            exit();
        }
    } else {
        $id = $_POST['id'];
        $data = array(
            'nama' => $_POST['editNama'],
            'keterangan' => $_POST['editKeterangan'],
            'satuan' => $_POST['editSatuan']
        );

        if ($barang->update($id, $data)) {
            header("Location: index.php?success=edit");
            exit();
        }
        else {
            header("Location: index.php?success=no");
            exit();
        }
    }
    
}
