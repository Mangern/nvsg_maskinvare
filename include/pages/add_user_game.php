<?php 
include 'include/api.php';

$user = $_SESSION["user"];

$game_response = $api_handle->fetch_games_platforms();

if($game_response["error"]) {
    echo $game_response["error_msg"];
    die();
}

$games = $game_response["result"];

?>

<script>
    var games = <?php echo json_encode($games); ?>;
</script>

<h1>Add game to <?php echo $user["first_name"]; ?></h1>

<form action="form_processors/add_user_game.php" method="POST">
    <label for="game">Game</label>
    <select name="game" id="select_game" onchange="on_select_game()">
        <?php 
        foreach($games as $row) {
            echo "<option value='" . $row["id_game"] . "'>" . $row["game_title"] . "</option>";
        }
        ?>
    </select>
    
    <br>
    
    <label for="platform">Platform</label>
    <select name="platform" id="select_platform">
        <?php 
        echo "<option value='" . $row["id_platform"] . "'>" . $row["name"] . "</option>";
        ?>
    </select>

    <br>

    <input type="submit" name="add_user_game" value="Add game" >
</form>
<script src="static/js/add_user_game.js"></script>