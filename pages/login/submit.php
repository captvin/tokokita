<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/tokokita1/api/user.php";
if (isset($_POST)) {

    $user = new user();
    $username = $_POST['username'];
    $password = $_POST['password'];


    if ($user->login($username, $password)) {
        header("Location: ../dashboard/index.php");
        exit();
    } else {
        header("Location: index.php?success=no");
        exit();
    }
}
