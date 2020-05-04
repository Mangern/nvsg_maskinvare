<?php 
include 'include/api.php';

if(!isset($_GET["user_id"])) {
    echo "Specify user id!";
    die();
}

$user_id = $_GET["user_id"];
$page = (isset($_GET["sub_page"]) ? $_GET["sub_page"] : PAGE_USER_DETAILS_PROFILE);


function special_sub($page) {
    global $user_id;
    return sub_url(PAGE_USER_DETAILS, $page) . "&user_id=$user_id";
}

function is_user_details_profile() {
    global $page;
    return $page == PAGE_USER_DETAILS_PROFILE;
}
function is_user_details_machines() {
    global $page;
    return $page == PAGE_USER_DETAILS_MACHINES;
}

function is_user_details_games() {
    global $page;
    return $page == PAGE_USER_DETAILS_GAMES;
}
?>

<ul class="navbar-left">
    <li class="sub-menu-item"><a class="<?php if(is_user_details_profile())echo "active-left"; ?>" href="<?php echo special_sub(PAGE_USER_DETAILS_PROFILE); ?>">Profile</a></li>
    <li class="sub-menu-item"><a class="<?php if(is_user_details_machines())echo "active-left"; ?>" href="<?php echo special_sub(PAGE_USER_DETAILS_MACHINES); ?>">Machines</a></li>
    <li class="sub-menu-item"><a class="<?php if(is_user_details_games())echo "active-left"; ?>" href="<?php echo special_sub(PAGE_USER_DETAILS_GAMES); ?>">Games</a></li>
</ul>

<div class="inner-content">
<?php 

include 'include/pages/sub_pages/' . $page . '.php';
?>
<div class="inner-content">