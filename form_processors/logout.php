<?php 
include '../include/meta.php';
session_start();
unset($_SESSION["user"]);
$_SESSION["message"] = "Logged out.";
redirect();
?>