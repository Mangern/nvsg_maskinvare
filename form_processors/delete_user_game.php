<?php 
include '../include/api.php';
include '../include/meta.php';

if(isset($_POST["delete_user_game"])) {
    session_start();
    $user_id = $_SESSION["user"]["id"];
    $game_id = $_POST["game_id"];
    $platform_id = $_POST["platform_id"];

    $response = $api_handle->delete_user_game($user_id, $game_id, $platform_id);

    if($response["error"]) {
        $_SESSION["error"] = $response["error_msg"];
    }
    redirect(PAGE_PROFILE);
}
?>