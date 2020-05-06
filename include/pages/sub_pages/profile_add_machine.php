<script>
    const ID_PLATFORM_PC = <?php echo ID_PLATFORM_PC; ?>;
</script>

<h1>Add machine</h1>
<form action="form_processors/add_machine.php" method="post">
    <label for="platform" id="label_platform">Platform</label>
    <select name="platform" id="select_platform" onchange="on_select_platform()">
        <?php
        $response = $api_handle->fetch_platforms();
        if ($response["error"]) {
            echo "<option>error</option>";
        } else {
            foreach ($response["result"] as $row) {
                $id = $row["id_platform"];
                $name = $row["name"];
                echo "\t\t<option value='$id' id='$id'>$name</option>\n";
            }
        }
        ?>

    </select>
    <br>
    <div id="container-pc-specs">

    <label for="name" id="label_name">Machine name</label>
    <input type="text" name="name" id="input_name">

    <br>

    <label for="storage_space" id="label_storage_space">Storage space</label>
    <input type="number" name="storage_space" id="input_storage_space">
    <br>


        <label for="ram" id="label_ram">RAM (GB)</label>
        <input type="number" name="ram" id="input_ram">
        <br>

        <label for="cpu" id="label_cpu">CPU</label>
        <select name="cpu" id="input_cpu">

            <?php
            $response = $api_handle->fetch_cpu();

            if ($response["error"]) {
                echo "<option>error</option>";
            } else {
                foreach ($response["result"] as $row) {
                    $id = $row["id_cpu"];
                    $name = $row["name"];

                    echo "<option value='$id'>$name</option>";
                }
            }
            ?>

        </select>

        <br>

        <label for="gpu" id="label_gpu">GPU</label>
        <select name="gpu" id="input_gpu">

            <?php
            $response = $api_handle->fetch_gpu();

            if ($response["error"]) {
                echo "<option>error</option>";
            } else {
                foreach ($response["result"] as $row) {
                    $id = $row["id_gpu"];
                    $name = $row["name"];
                    echo "<option value='$id'>$name</option>";
                }
            }

            ?>

        </select>

        <br>

    </div>

    <input type="submit" name="add_machine" value="Add machine">
</form>
<script src="static/js/add_user_machine.js">

</script>