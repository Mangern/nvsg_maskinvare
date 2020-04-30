<?php

include '../include/meta.php';
require '../include/api.php';

if(isset($_POST["add_machine"])) {
    session_start();

    $uid = $_SESSION["user"]["id"];

    $platform_id = $_POST["platform"];
    $response = array();
    if($platform_id != ID_PLATFORM_PC) {
        
        $response = $api_handle->insert_default_machine($uid, $platform_id);
    }
    else {
        $name = $_POST["name"];
        $ram = $_POST["ram"];
        $cpu = $_POST["cpu"];
        $gpu = $_POST["gpu"];
        $storage = $_POST["storage_space"];
    
        $response = $api_handle->insert_machine($uid, $name, $ram, $cpu, $gpu, $storage);
    }

    

    if($response["error"]) {
        $_SESSION["error"] = $response["error_msg"];
    }
    redirect(PAGE_PROFILE);
}
?>