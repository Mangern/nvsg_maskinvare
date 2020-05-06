<?php 
include '../include/api.php';
include '../include/meta.php';

if(isset($_POST["register_default_machine"])) {
    $platform_id = $_POST["platform_id"];
    $platform_name = $_POST["platform_name"];

    $ram = $_POST["ram"];

    $storage_space = $_POST["storage_space"];

    $cpu_name = $platform_name . " CPU";
    $gpu_nume = $platform_name . " GPU";

    $response = $api_handle->register_default_machine($platform_id, $platform_name, $ram, $storage_space, $cpu_name, $gpu_nume);

    session_start();
    if($response["error"]) {
        $_SESSION["error"] = $response["error_msg"];
    }
    else {
        $_SESSION["message"] = "Successfully inserted default machine for $platform_name";
    }
}
redirect(PAGE_ADMIN, PAGE_ADMIN_PLATFORMS);
?>