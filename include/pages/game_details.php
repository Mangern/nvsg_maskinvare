<?php 
include 'include/api.php';
$user = $_SESSION["user"];

if(!isset($_GET["game_id"])) {
    redirect(PAGE_GAMES);
}
$game_id = $_GET["game_id"];

$response = $api_handle->fetch_game_details($game_id);

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
        int "ram" ram
        string "cpu" cpu_name
        string "gpu" gpu_name
        int "storage_space" storage_space
*/
$game_details = $response["result"];
?>
<h1><?php echo $game_details["title"]; ?></h1>
<h2>Spec Requirements</h2>
<p>Viewing requirements for: </p>
<select id="input_requirement_platform">
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
    <tr><th>Ram</th><th>CPU</th><th>GPU</th><th>Storage Space</th></tr>
</thead>
<tbody>
    <?php 
        foreach($game_details["platforms"] as $row) {
            $id = $row["id"];
            $machine = $row["minimum_machine"];

            echo "<tr id='tr_platform_$id'>";

            echo "<td>" . $machine["ram"] . " GB</td>";
            echo "<td>" . $machine["cpu"] . "</td>";
            echo "<td>" . $machine["gpu"] . "</td>";
            echo "<td>" . $machine["storage_space"] . " GB</td>";

            echo "</tr>";
        }
    ?>
</tbody>
</table>