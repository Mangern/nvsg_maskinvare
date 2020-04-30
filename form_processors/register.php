<?php
require '../include/api.php';
include '../include/meta.php';


if(isset($_POST["register"])) {
    $email = $_POST["email"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $password = $_POST["password"];
    $password_confirm = $_POST["password_confirm"];

    if($password != $password_confirm) {
        $_SESSION["error"] = "Password did not equal Confirm password";
        redirect(PAGE_REGISTER);
    }
    $response = $api_handle->register($email, $password, $first_name, $last_name);
    session_start();

    if($response["error"]) {
        $_SESSION["error"] = $response["error_msg"];
        redirect(PAGE_REGISTER);
    }
    else {
        $_SESSION["user"] = $response["result"];
        redirect(PAGE_HOME);
    }
}
else {
    redirect();
}

?>