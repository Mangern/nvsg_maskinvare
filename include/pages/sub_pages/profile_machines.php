<h1>Machines</h1>

<table>
<thead>
    <tr><th>Machine name</th><th>RAM</th><th>CPU</th><th>GPU</th><th>Storage space</th><th></th></tr>
</thead>
<tbody>
    
    <?php 
    
    $response = $api_handle->fetch_machines($user["id"]);

    function delete_machine_button($id) {
        return "<form method='POST' action='form_processors/delete_machine.php'>
                    <input type='hidden' name='id_machine' value='$id'>
                    <input class='button-delete' type='submit' name='delete_machine' value='Delete'>
                </form>";
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
            echo "<tr><td>$name</td><td>$ram GB</td><td>$cpu</td><td>$gpu</td><td>$sspace GB</td><td>" . delete_machine_button($id) . "</td></tr>";
        }
    }

    
    ?>

</tbody>
</table>

<a href="<?php echo sub_url(PAGE_PROFILE, PAGE_PROFILE_ADD_MACHINE); ?>">Add machine</a>
