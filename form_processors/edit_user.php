<?php

include '../include/api.php';
include '../include/meta.php';

if(isset($_POST["edit_user"])) {
    session_start();
    $uid = $_SESSION["user"]["id"];
    $email = $_POST["email"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $nickname = $_POST["nickname"];

    $response = $api_handle->update_user($uid, $email, $first_name, $last_name, $nickname);

    if($response["error"]) {
        $_SESSION["error"] = "Could not update user: " . $response["error_msg"];
        redirect(PAGE_PROFILE);
    }
    $_SESSION["user"] = $response["result"];
    redirect(PAGE_PROFILE);
}

?>