<?php 
include '../include/api.php';
include '../include/meta.php';

if(isset($_POST["add_account"])) {
    $service = $_POST["service"];
    $tag = $_POST["account_tag"];

    session_start();
    $uid = $_SESSION["user"]["id"];

    $response = $api_handle->add_account($uid, $service, $tag);

    if($response["error"]) {
        $_SESSION["error"] = $response["error_msg"];
    }
    
}
redirect(PAGE_PROFILE, PAGE_PROFILE_ACCOUNTS);
?>