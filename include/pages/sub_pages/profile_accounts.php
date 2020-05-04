<h1>Accounts</h1>
<?php 
$accounts_response = $api_handle->fetch_user_accounts($user["id"]);
$services_response = $api_handle->fetch_third_party_service();

if($accounts_response["error"]) {
    echo $accounts_response["error_msg"];
    die();
}
if($services_response["error"]) {
    echo $services_response["error_msg"];
    die();
}

$accounts = $accounts_response["result"];
$all_services = $services_response["result"];

function remove_service($id) {
    global $all_services;

    $new_services = array();

    foreach($all_services as $row) {
        if($row["id_service"] != $id) {
            array_push($new_services, $row);
        }
    }

    $all_services = $new_services;
}
?>

<table class="no-divider">
<thead>
    <tr><th>Service</th><th>Account Tag</th></tr>
</thead>
<tbody>
<form action="form_processors/edit_accounts.php" method="post">

    <?php 
    
    for($i = 0; $i < count($accounts); $i++) {
        $row = $accounts[$i];
        echo "<tr>";

        echo "<td>" . $row["name"] . "</td>";

        echo "<td><input type='text' name='edit_tag_" . $row["id_service"] . "' value='" . $row["account_tag"] . "'/></td>";

        remove_service($row["id_service"]);
        
        if($i == count($accounts) - 1) {
            echo "<td>";

            echo "<input type='submit' name='edit_accounts' value='Save changes'>";

            echo "</td>";
        }

        echo "</tr>";
    }
    
    ?>
</form>
<form action="form_processors/add_account.php" method="POST">
    <tr id="tr_add_service" style="visibility: hidden;">
        <td>
            <select name="service" id="select_service">
            
                <?php 
                foreach($all_services as $row) {
                    $id = $row["id_service"];
                    $name = $row["name"];
                    echo "<option value='$id'>$name</option>";
                }
                
                ?>
            
            </select>
        </td>
    
        <td><input type="text" name="account_tag" placeholder="Account tag"></td>
        <td><input type="submit" value="Add account" name="add_account"></td>
    </tr>
    </form>

    <tr>
        <td><button id="button_add_service" onclick="add_service()">Add service</button></td>
    </tr>
</tbody>
</table>
<script>
    function add_service() {
        document.getElementById("tr_add_service").style.visibility = "visible";
        document.getElementById("button_add_service").style.visibility = "hidden";
    }
</script>