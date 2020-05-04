<?php 
include 'include/api.php';

$response = $api_handle->fetch_user();

if($response["error"]) {
    echo $response["error_msg"];
    die();
}

$users = $response["result"];
?>

<h1>All Users</h1>
<table>
<thead>
    <tr><th>Name</th><th>Nickname</th></tr>
</thead>
<tbody>
    <?php 
    foreach($users as $row) {

        if($row["id_user"] == $_SESSION["user"]["id"])continue;

        echo "<tr>";

        $first_name = $row["first_name"];
        $last_name = $row["last_name"];
        $nickname = ($row["nickname"]!= NULL) ? $row["nickname"] : "-";

        echo "<td>$first_name $last_name</td>";
        echo "<td>$nickname</td>";


        echo "<td><a href='" . menu_url(PAGE_USER_DETAILS) . "&user_id=" . $row["id_user"] . "'>Details</a></td>"; 

        echo "</tr>";
    }
    ?>
</tbody>
</table>