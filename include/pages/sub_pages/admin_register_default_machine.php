<?php 
if(!isset($_POST["platform_id"])) {
    echo "specify platform id!";
    die();
}

$platform_id = $_POST["platform_id"];
$platform_name = $_POST["platform_name"];
?>
<h1>Register default machine for <?php echo $platform_name; ?></h1>

<form action="form_processors/register_default_machine" method="POST">

<label for="ram">RAM</label>
<input type="number" name="ram" placeholder="69 GB">

<label for="storage_space">Storage Space</label>
<input type="number" name="storage_space" placeholder="420 GB">

<input type="hidden" name="cpu_name" value="<?php echo $platform_name; ?> CPU">
<input type="hidden" name="gpu_name" value="<?php echo $platform_name; ?> GPU">

<input type="submit" value="Register" name="register_default_machine">

</form>