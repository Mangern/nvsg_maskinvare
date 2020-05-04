<?php 
include 'include/api.php';

$response = $api_handle->fetch_platforms();

function handle_error($resp) {
    global $_SESSION;
    if($resp["error"]) {
        $_SESSION["error"] = $resp["error_msg"];
        redirect(PAGE_GAMES);
    }
}

handle_error($response);

$platforms = $response["result"];

$response = $api_handle->fetch_cpu();
handle_error($response);

$cpu_list = $response["result"];

$response = $api_handle->fetch_gpu();
handle_error($response);

$gpu_list = $response["result"];
?>

<script>
    const ID_PLATFORM_PC = <?php echo ID_PLATFORM_PC; ?>;
    var cpu_list = <?php echo json_encode($cpu_list); ?>;
    var gpu_list = <?php echo json_encode($gpu_list); ?>;
    var platforms = <?php echo json_encode($platforms); ?>;
</script>

<ul class="navbar-left">
    <li class="sub-menu-item"><a class="active-left" href="">Games</a></li>
</ul>

<div class="inner-content">
<h1>Register new game</h1>
<form id="form_register_game" action="form_processors/register_game.php" method="POST">
    <label for="title">Title</label>
    <input type="text" name="title" placeholder="e.g. Minecraft">

    <input type="hidden" name="num_platforms" id="input_num_platforms" value="0">

    <div id="container_dynamic_inputs">

    </div>
    <button type="button" id="button_add_platform" onclick="add_platform()">Add platform</button>

    <br>

    <input type="submit" name="register_game" id="input_submit" value="Register" style="visibility: hidden;">
</form>
</div>
<script src="static/js/register_game.js"></script>