<?php 
$user = $_SESSION["user"];

include 'include/api.php';


?>

<ul>
    <li><a href="<?php echo sub_url(PAGE_PROFILE, PAGE_PROFILE_SETTINGS); ?>">Profile Settings</a></li>
    <li><a href="<?php echo sub_url(PAGE_PROFILE, PAGE_PROFILE_MACHINES); ?>">My Machines</a></li>
    <li><a href="<?php echo sub_url(PAGE_PROFILE, PAGE_PROFILE_GAMES); ?>">My Games</a></li>
    <li><a href="<?php echo sub_url(PAGE_PROFILE, PAGE_PROFILE_ACCOUNTS); ?>">Accounts</a></li>
    <li><a href="form_processors/logout.php">Log out</a></li>
</ul>

<?php 
$page = (isset($_GET["sub_page"]) ? $_GET["sub_page"] : PAGE_PROFILE_SETTINGS);

include 'include/pages/sub_pages/' . $page . '.php';
?>