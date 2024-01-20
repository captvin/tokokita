<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/api/user.php";
if (isset($_POST)) {

    $user = new user('kasir');

    if (isset($_POST['new'])) {
        $data = array(
            'username' => $_POST['addUsername'],
            'password' => $_POST['addPassword'],
            'first' => $_POST['addFirstName'],
            'last' => $_POST['addLastName'],
            'phone' => $_POST['addPhone'],
            'address' => $_POST['addAddress'],
            'role' => $_POST['addRole']
        );
        if ($user->create($data)) {
            header("Location: index.php?success=add");
            exit();
        }
        else{
            header("Location: index.php?success=no");
            exit();
        }
    } else {
        $id = $_POST['userId'];
        $data = array(
            'username' => $_POST['editUsername'],
            'first' => $_POST['editFirstName'],
            'last' => $_POST['editLastName'],
            'phone' => $_POST['editPhone'],
            'address' => $_POST['editAddress'],
            'role' => $_POST['editRole']
        );

        if ($user->update($id, $data)) {
            header("Location: index.php?success=edit");
            exit();
        }
        else {
            header("Location: index.php?success=no");
            exit();
        }
    }
    
}
