<?php 
include 'include/api.php';

if(!isset($_GET["user_id"])) {
    echo "Specify user id!";
    die();
}

$user_id = $_GET["user_id"];

function special_sub($page) {
    global $user_id;
    return sub_url(PAGE_USER_DETAILS, $page) . "&user_id=$user_id";
}
?>

<ul>
    <li><a href="<?php echo special_sub(PAGE_USER_DETAILS_PROFILE); ?>">Profile</a></li>
    <li><a href="<?php echo special_sub(PAGE_USER_DETAILS_MACHINES); ?>">Machines</a></li>
    <li><a href="<?php echo special_sub(PAGE_USER_DETAILS_GAMES); ?>">Games</a></li>
</ul>

<?php 
$page = (isset($_GET["sub_page"]) ? $_GET["sub_page"] : PAGE_USER_DETAILS_PROFILE);

include 'include/pages/sub_pages/' . $page . '.php';
?>