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
                $title = $row["game_title"];
                $platforms = $row["platforms"];

                echo "<tr>";

                echo "<td>$title</td>";

                $platformstring = "";

                foreach($platforms as $platform) {
                    $platformstring .= $platform . ',';
                }
                $platformstring = substr($platformstring, 0, strlen($platformstring) - 1);

                echo "<td>$platformstring</td>";

                echo "</tr>";
            }
        }
    ?>
</tbody>
</table>