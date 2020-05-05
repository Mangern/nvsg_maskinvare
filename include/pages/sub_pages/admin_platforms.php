<?php
$response = $api_handle->fetch_platforms();

if ($response["error"]) {
    echo $response["error_msg"];
    die();
}

$platforms = $response["result"];
?>

<h1>Platforms</h1>

<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Default machine</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($platforms as $row) {
            echo "<tr>";

            $id = $row["id_platform"];
            $name = $row["name"];
            $default_machine = $row["id_default_machine"];
            echo "<td>$id</td>";
            echo "<td>$name</td>";

            if($default_machine == NULL) {
                echo "<td>";

                echo "<form class='nomargin' action='" . sub_url(PAGE_ADMIN, PAGE_ADMIN_REGISTER_DEFAULT_MACHINE) . "' method='POST'>";

                echo "<input type='hidden' name='platform_name' value='$name'>";
                echo "<input type='hidden' name='platform_id' value='$id'>";
                echo "<input type='submit' name='register_default_machine' value='Register Default Machine'>";

                echo "</form>";

                echo "</td>";
            }
            else {
                echo "<td>$default_machine</td>";
            }

            echo "</tr>";
        }
        ?>

        <tr>
            <form action="form_processors/add_platform.php" method="POST">
                <td>New platform</td>

                <td><input type="text" placeholder="Platform name" name="platform_name"></td>

                <td><input type="submit" value="Register" name="add_platform"></td>
            </form>
        </tr>
    </tbody>
</table>