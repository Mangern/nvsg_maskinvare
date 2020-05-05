<?php 
$response = $api_handle->fetch_platforms();

if($response["error"]) {
    echo $response["error_msg"];
    die();
}

$platforms = $response["result"];
?>

<h1>Platforms</h1>

<table>
<thead>
    <tr><th>Id</th><th>Name</th></tr>
</thead>
<tbody>
    <?php 
    foreach($platforms as $row) {
        echo "<tr>";

        $id = $row["id_platform"];
        $name = $row["name"];
        echo "<td>$id</td>";
        echo "<td>$name</td>";

        echo "</tr>";
    }
    ?>
</tbody>
</table>
<a href="">Add platform </a>