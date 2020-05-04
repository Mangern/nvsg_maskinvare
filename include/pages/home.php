<?php 
$first_name = $_SESSION["user"]["first_name"];
$last_name = $_SESSION["user"]["last_name"];
?>

<div id="container_home">
<div class="bg-image"></div>

<div class="bg-text">
  <h1>Welcome</h1>
  <p><?php echo "$first_name $last_name"; ?></p>
</div>
</div>