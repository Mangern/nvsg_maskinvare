<?php 
$user = $_SESSION["user"];

include 'include/api.php';


?>

<h1>Profile</h1>
<form action="form_processors/edit_user.php" method="POST">
    <label for="email">Email</label>
    <input type="text" name="email" value="<?php echo $user["email"]; ?>">

    <br>

    <label for="first_name">First name</label>
    <input type="text" name="first_name" value="<?php echo $user["first_name"]; ?>">

    <br>

    <label for="last_name">Last name</label>
    <input type="text" name="last_name" value="<?php echo $user["last_name"]; ?>">

    <br>

    <label for="nickname">Nickname</label>
    <input type="text" name="nickname" value="<?php echo $user["nickname"]; ?>">

    <br>

    <input type="submit" name="edit_user" value="Save changes">
</form>

<h1>Machines</h1>

<table>
<thead>
    <tr><th>Machine name</th><th>RAM</th><th>CPU</th><th>GPU</th><th>Storage space</th></tr>
</thead>
<tbody>
    
    <?php 
    
    $response = $api_handle->fetch_machines($user["id"]);

    function delete_button($id) {
        return "<form method='POST' action='form_processors/delete_machine.php'><input type='hidden' name='id_machine' value='$id'><input type='submit' name='delete_machine' value='Delete'></form>";
    }

    if(!$response["error"]) {
        $machines = $response["result"];

        if(count($machines) == 0) {
            echo "<p>No machines</p>";
        }

        
        
        foreach($machines as $row) {
            $id = $row["id_machine"];
            $name = $row["name"];
            $ram = $row["ram"];
            $cpu = $row["cpu_name"];
            $gpu = $row["gpu_name"];
            $sspace = $row["storage_space"];
            echo "<tr><td>$name</td><td>$ram GB</td><td>$cpu</td><td>$gpu</td><td>$sspace GB</td><td>" . delete_button($id) . "</td></tr>";
        }
    }

    
    ?>

</tbody>
</table>

<a href="<?php echo menu_url(PAGE_ADD_MACHINE); ?>">Add machine</a>

<h1>Games</h1>
<table>
<thead>

</thead>
<tbody>
</tbody>
</table>

