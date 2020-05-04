<h1>Games</h1>
<table>
<thead>
    <tr><th>Game Title</th><th>Platform (s)</th><th></th></tr>
</thead>
<tbody>
    <?php 
    $response = $api_handle->fetch_user_games($user["id"]);

    function delete_game_button($id, $platform) {
        return "<form method='POST' action='form_processors/delete_user_game.php'>
                    <input type='hidden' name='game_id' value='$id'>
                    <input type='hidden' name='platform_id' value='$platform'>
                    <input class='button-delete' type='submit' name='delete_user_game' value='Delete'>
                </form>";
    }

    if(!$response["error"]) {
        $games = $response["result"];

     
        foreach($games as $row) {
            echo "<tr>";

            $title = $row["title"];
            $platform = $row["name"];
            echo "<td>$title</td>";
            echo "<td>$platform</td>";
            echo "<td>" . delete_game_button($row["id_game"], $row["id_platform"]) . "</td>";
            echo "</tr>";
        }
        
    }
    ?>
</tbody>
</table>

<a href="<?php echo sub_url(PAGE_PROFILE, PAGE_PROFILE_ADD_GAME); ?>">Add game</a>