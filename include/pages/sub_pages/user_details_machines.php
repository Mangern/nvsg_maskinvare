<?php 
$response = $api_handle->fetch_machines($user_id);


if($response["error"]) {
    echo $response["error_msg"];
    die();
}

$machines = $response["result"];
?>


<h1>Machines</h1>

<table>
<thead>
    <tr><th>Machine name</th><th>RAM</th><th>CPU</th><th>GPU</th><th>Storage space</th></tr>
</thead>
<tbody>
    
    <?php 
    
    
        foreach($machines as $row) {
            $id = $row["id_machine"];
            $name = $row["name"];
            $ram = $row["ram"];
            $cpu = $row["cpu_name"];
            $gpu = $row["gpu_name"];
            $sspace = $row["storage_space"];
            echo "<tr><td>$name</td><td>$ram GB</td><td>$cpu</td><td>$gpu</td><td>$sspace GB</td></tr>";
        }
    

    
    ?>

</tbody>
</table>