<?php 
include '../include/api.php';
include '../include/meta.php';

if(isset($_POST["add_user_game"])) {
    $game_id = $_POST["game"];
    $platform_id = $_POST["platform"];

    session_start();
    $uid = $_SESSION["user"]["id"];

    $response = $api_handle->insert_user_game($uid, $game_id, $platform_id);

    if($response["error"]) {
        $_SESSION["error"] = "Failed to add game: user already has it.";
    }
    else $_SESSION["message"] = "Game was added";
    redirect(PAGE_PROFILE, PAGE_PROFILE_GAMES);
}
?>