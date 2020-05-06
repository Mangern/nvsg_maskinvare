<?php 
include '../include/api.php';
include '../include/meta.php';

if(isset($_POST["edit_accounts"])) {
    // Find ids
    session_start();
    $uid = $_SESSION["user"]["id"];
    $response = $api_handle->fetch_user_accounts($uid);

    if($response["error"]) {
        $_SESSION["error"] = $response["error_msg"];
        redirect(PAGE_PROFILE, PAGE_PROFILE_ACCOUNTS);
    }

    $accounts = $response["result"];

    foreach($accounts as $row) {
        $id_service = $row["id_service"];

        $new_name = $_POST["edit_tag_" . $id_service];

        $api_handle->update_account($uid, $id_service, $new_name);
    }

    $_SESSION["message"] = "Accounts updated";
}
redirect(PAGE_PROFILE, PAGE_PROFILE_ACCOUNTS);
?>