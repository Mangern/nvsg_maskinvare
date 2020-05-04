<?php 
include '../include/api.php';
include '../include/meta.php';

if(isset($_POST["delete_machine"])) {
    $id = $_POST["id_machine"];

    session_start();
    $uid = $_SESSION["user"]["id"];

    $response = $api_handle->delete_machine($uid, $id);

    if($response["error"]) {
        $_SESSION["error_msg"] = $response["error_msg"];
    }
    redirect(PAGE_PROFILE, PAGE_PROFILE_MACHINES);
}
?>