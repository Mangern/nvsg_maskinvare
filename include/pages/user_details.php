<?php 
include 'include/api.php';

if(!isset($_GET["user_id"])) {
    echo "Specify user id!";
    die();
}

$user_id = $_GET["user_id"];

$response = $api_handle->fetch_user_details($user_id);
$accounts_response = $api_handle->fetch_user_accounts($user_id);

if($response["error"]) {
    echo $response["error_msg"];
    die();
}

if($accounts_response["error"]) {
    echo $accounts_response["error_msg"];
    die();
}

$user = $response["result"];
if($user["nickname"] == NULL)$user["nickname"] = "-";

$accounts = $accounts_response["result"];
?>

<h1>User Profile</h1>
<h3>Name</h3>
<p><?php echo $user["first_name"] . " " . $user["last_name"]; ?></p>

<h3>Email</h3>
<p><?php echo $user["email"]; ?></p>

<h3>Nickname</h3>
<p><?php echo $user["nickname"]; ?></p>



<h1>Machines</h1>

<table>
<thead>
    <tr><th>Machine name</th><th>RAM</th><th>CPU</th><th>GPU</th><th>Storage space</th></tr>
</thead>
<tbody>
    
    <?php 
    
    $response = $api_handle->fetch_machines($user["id"]);


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
            echo "<tr><td>$name</td><td>$ram GB</td><td>$cpu</td><td>$gpu</td><td>$sspace GB</td></tr>";
        }
    }

    
    ?>

</tbody>
</table>


<h1>Games</h1>
<table>
<thead>
    <tr><th>Game Title</th><th>Platform (s)</th></tr>
</thead>
<tbody>
    <?php 
    $response = $api_handle->fetch_user_games($user["id"]);



    if(!$response["error"]) {
        $games = $response["result"];

     
        foreach($games as $row) {
            echo "<tr>";

            $title = $row["title"];
            $platform = $row["name"];
            echo "<td>$title</td>";
            echo "<td>$platform</td>";
            echo "</tr>";
        }
        
    }
    ?>
</tbody>
</table>

<h1>Accounts</h1>
<table>
<thead>
    <?php if(count($accounts) > 0) echo "<tr><th>Service</th><th>Account tag</th></tr>"; ?>
</thead>
<tbody>
    <?php 
        foreach($accounts as $row) {
            $service = $row["name"];
            $tag = $row["account_tag"];
            echo "<tr><td>$service</td><td>$tag</td></tr>"; 
        }
    ?>
</tbody>
</table>