<?php 
include 'include/api.php';
$user = $_SESSION["user"];

if(!isset($_GET["game_id"])) {
    redirect(PAGE_GAMES);
}
$game_id = $_GET["game_id"];

$response = $api_handle->fetch_game_details($game_id, $user["id"]);

if($response["error"]) {
    $_SESSION["error"] = $response["error_msg"];
    redirect(PAGE_GAMES);
}
/*

string title
vector<map> platforms
    int "id" id
    string "name" name
    map "minimum_machine"
        int "storage_space" storage_space
        int "ram" ram
        string "cpu" cpu_name
        string "gpu" gpu_name
    bool "user_can_play" user_can_play
*/
$game_details = $response["result"];
?>

<script>
    var game_title = <?php echo json_encode($game_details["title"]); ?>;
    var platforms = <?php echo json_encode($game_details["platforms"]); ?>;
</script>

<h1><?php echo $game_details["title"]; ?></h1>
<h2>Spec Requirements</h2>
<p>Viewing requirements for: </p>
<select id="input_requirement_platform" onchange="on_platform_select()">
    <?php 
    foreach($game_details["platforms"] as $row) {
        $id = $row["id"];
        $name = $row["name"];
        echo "<option value='$id'>$name</option>";
    }
    ?>
</select>

<table>
<thead>
    <tr><th>Storage Space</th><th>Ram</th><th>CPU</th><th>GPU</th></tr>
</thead>
<tbody id="tbody_game_requirements">
</tbody>
</table>
<p id="p_can_play_text">You can play <?php echo $game_details["title"]; ?> on PC</p>

<h2>Users</h2>
<p>These users have <?php echo $game_details["title"]; ?></p>
<?php



?>
<script src="static/js/game_details.js"></script>