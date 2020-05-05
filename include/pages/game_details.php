<?php
include 'include/api.php';
$user = $_SESSION["user"];

if (!isset($_GET["game_id"])) {
    redirect(PAGE_GAMES);
}
$game_id = $_GET["game_id"];

$response = $api_handle->fetch_game_details($game_id, $user["id"]);

if ($response["error"]) {
    echo $response["error_msg"];
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
vector<map> users
    data
*/
$game_details = $response["result"];

?>

<script>
    var game_title = <?php echo json_encode($game_details["title"]); ?>;
    var platforms = <?php echo json_encode($game_details["platforms"]); ?>;
</script>

<ul class="navbar-left">
    <li class="sub-menu-item"><a class="active-left" href="<?php echo menu_url(PAGE_GAMES); ?>">Games</a></li>
</ul>

<div class="inner-content">
    <h1><?php echo $game_details["title"]; ?></h1>
    <h2>Spec Requirements</h2>
    <p>Viewing requirements for: </p>
    <select id="input_requirement_platform" onchange="on_platform_select()">
        <?php
        foreach ($game_details["platforms"] as $row) {
            $id = $row["id"];
            $name = $row["name"];
            echo "<option value='$id'>$name</option>";
        }
        ?>
    </select>

    <table>
        <thead>
            <tr>
                <th>Storage Space</th>
                <th>Ram</th>
                <th>CPU</th>
                <th>GPU</th>
            </tr>
        </thead>
        <tbody id="tbody_game_requirements">
        </tbody>
    </table>
    <p id="p_can_play_text">You can play <?php echo $game_details["title"]; ?> on PC</p>

    <h2>Users</h2>
    <p>These users have <?php echo $game_details["title"]; ?></p>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Nickname</th>
                <th>Platform</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($game_details["users"] as $row) {
                $uid = $row["id_user"];
                $first_name = $row["first_name"];
                $last_name = $row["last_name"];

                $nickname = $row["nickname"];

                $platform = $row["name"];

                echo "<tr><td>$first_name $last_name</td><td>$nickname</td><td>$platform</td>";

                echo "<td>";
                if ($uid == $user["id"]) {
                    echo "<a href='" . menu_url(PAGE_PROFILE) . "'>Details</a>";
                } else {
                    echo "<a href='" . menu_url(PAGE_USER_DETAILS) . "&user_id=" . $uid . "'>Details</a>";
                }
                echo "</td>";
                echo "</tr>";
            }


            ?>
        </tbody>
    </table>
</div>
<script src="static/js/game_details.js"></script>