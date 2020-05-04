<?php 
$response = $api_handle->fetch_user_games($user_id);



if($response["error"]) {
    echo $response["error_msg"];
    die();
}


$games = $response["result"];
?>
<h1>Games</h1>
<table>
<thead>
    <tr><th>Game Title</th><th>Platform (s)</th></tr>
</thead>
<tbody>
    <?php 
  
        foreach($games as $row) {
            echo "<tr>";

            $title = $row["title"];
            $platform = $row["name"];
            echo "<td>$title</td>";
            echo "<td>$platform</td>";
            echo "</tr>";
        }
        
    
    ?>
</tbody>
</table>