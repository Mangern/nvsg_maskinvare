<?php 
include 'include/api.php';

$user = $_SESSION["user"];
?>

<h1>Add game to <?php echo $user["first_name"]; ?></h1>

<form action="form_processors/add_user_game.php" method="POST">
    <label for="game">Game</label>
    <select name="game" id="input_game">
        <?php 
        $game_response = $api_handle->fetch_games_platforms();

        if(!$game_response["error"]) {
            $games = $game_response["result"];

            foreach($games as $row) {
                echo "<option value='" . $row["id_game"] . "'>" . $row["game_title"] . "</option>";
            }
        }
        else {
            $error = $game_response["error_msg"];
            echo "<option>$error</option>";
        }
        ?>
    <option value="-1">New Game</option>
    </select>
    
    <br>
    
    <label for="platform">Platform</label>
    <select name="platform" id="input_platform">
        <?php 
        $platform_response = $api_handle->fetch_platforms();

        if(!$platform_response["error"]) {
            $platforms = $platform_response["result"];

            foreach($platforms as $row) {
                echo "<option value='" . $row["id_platform"] . "'>" . $row["name"] . "</option>";
            }
        }
        else {
            $error = $platform_response["error_msg"];
            echo "<option>$error</option>";
        }
        ?>
    </select>

    <br>

    <input type="submit" name="add_user_game" value="Add game" >
</form>