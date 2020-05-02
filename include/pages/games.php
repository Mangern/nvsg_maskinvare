<?php 
$user = $_SESSION["user"];

include 'include/api.php';
?>

<h1>Games</h1>
<table>
<thead>
    <tr><th>Game title</th><th>Platform (s)</th><th>Playable</th></tr>
</thead>
<tbody>
    <?php 
        $response = $api_handle->fetch_games_platforms();

        if(!$response["error"]) {
            $games = $response["result"];

            if(count($games) == 0) {
                echo "No games";
            }
            
            foreach($games as $row) {
                $game_id = $row["id_game"];
                $title = $row["game_title"];
                $platforms = $row["platforms"]; // id, name

                echo "<tr>";

                echo "<td>$title</td>";

                $platformstring = "";

                foreach($platforms as $platform) {
                    $platformstring .= $platform["name"] . ',';
                }
                $platformstring = substr($platformstring, 0, strlen($platformstring) - 1);

                echo "<td>$platformstring</td>";

                // Can play
                $can_play_response = $api_handle->user_can_play_game($user["id"], $game_id);

                if($can_play_response["error"]) {
                    echo "<td>?</td>";
                }
                else {
                    $can_play = false;

                    foreach($can_play_response["result"] as $row) {
                        if($row["verdict"]) {
                            $can_play = true;
                            break;
                        }
                    }

                    if($can_play) {
                        echo "<td>Yes</td>";
                    }
                    else {
                        echo "<td>No</td>";
                    }

                    echo "<td><a href='" . menu_url(PAGE_GAME_DETAILS) . "&game_id=" . $game_id . "'>Details</a></td>";
                }

                echo "</tr>";
            }
        }
    ?>
</tbody>
</table>

<a href="<?php echo menu_url(PAGE_REGISTER_GAME); ?>">Register new game</a>