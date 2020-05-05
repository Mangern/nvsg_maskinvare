<?php 
include '../include/meta.php';
include '../include/api.php';

if(isset($_POST["add_platform"])) {
    $name = $_POST["platform_name"];

    $response = $api_handle->insert_platform($name);

    session_start();
    if($response["error"]) {
        $_SESSION["error"] = $response["error_msg"];
    }
}

redirect(PAGE_ADMIN, PAGE_ADMIN_PLATFORMS);
?>