<?php 

include '../include/api.php';
include '../include/meta.php';

if(isset($_POST["register_game"])) {

    $title = $_POST["title"];

    $num_platforms = $_POST["num_platforms"];

    $response = $api_handle->register_game($title);
    session_start();

    if($response["error"]) {
        $_SESSION["error"] = $response["error_msg"];
        redirect(PAGE_GAMES);
    }

    $game_id = $response["result"]["id"];

    for($i = 0; $i < $num_platforms; $i++) {
        // 
        $platform_id = $_POST["platform_" . $i];


        $storage_space = $_POST["storage_space_" + $i];

        $requirements = array(
            "storage_space" => $storage_space
        );

        if($platform_id == ID_PLATFORM_PC) {
            $cpu
        }
        else {
            
        }
        
        // Sketchy: ignore response
        $api_handle->register_platform_to_game($game_id, $platform_id, $requirements);
    }

}
else {
    redirect(PAGE_GAMES);
}

?>