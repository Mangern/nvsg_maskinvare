<?php 
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