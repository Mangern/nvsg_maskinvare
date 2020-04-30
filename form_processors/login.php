<?php
include '../include/api.php';
include '../include/meta.php';

if(isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $response = $api_handle->verify_user($email, $password);
    session_start();
    if($response["error"]) {
        $_SESSION["error"] = $repsonse["error_msg"];
        redirect(PAGE_LOGIN);
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