<?php 
$user = $_SESSION["user"];

include 'include/api.php';

$page = (isset($_GET["sub_page"]) ? $_GET["sub_page"] : PAGE_PROFILE_SETTINGS);


function is_settings() {
    global $page;
    return $page == PAGE_PROFILE_SETTINGS;
}

function is_profile_machines() {
    global $page;
    switch($page) {
        case PAGE_PROFILE_ADD_MACHINE:
        case PAGE_PROFILE_MACHINES:
            return true;
    }
    return false;
}

function is_profile_games() {
    global $page;
    switch($page) {
        case PAGE_PROFILE_GAMES:
        case PAGE_PROFILE_ADD_GAME:
            return true;
    }
    return false;
}

function is_accounts() {
    global $page;
    return $page == PAGE_PROFILE_ACCOUNTS;
}
?>

<ul class="navbar-left">
    <li class="sub-menu-item"><a class="<?php if(is_settings())echo "active-left"; ?>" href="<?php echo sub_url(PAGE_PROFILE, PAGE_PROFILE_SETTINGS); ?>">Profile Settings</a></li>
    <li class="sub-menu-item"><a class="<?php if(is_profile_machines())echo "active-left"; ?>" href="<?php echo sub_url(PAGE_PROFILE, PAGE_PROFILE_MACHINES); ?>">My Machines</a></li>
    <li class="sub-menu-item"><a class="<?php if(is_profile_games())echo "active-left"; ?>" href="<?php echo sub_url(PAGE_PROFILE, PAGE_PROFILE_GAMES); ?>">My Games</a></li>
    <li class="sub-menu-item"><a class="<?php if(is_accounts())echo "active-left"; ?>" href="<?php echo sub_url(PAGE_PROFILE, PAGE_PROFILE_ACCOUNTS); ?>">Accounts</a></li>
    <li class="sub-menu-item"><a href="form_processors/logout.php">Log out</a></li>
</ul>

<div class="inner-content">
<?php 

include 'include/pages/sub_pages/' . $page . '.php';
?>
</div>