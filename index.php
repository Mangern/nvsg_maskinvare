<?php 
include 'include/meta.php';

session_start();

$page = (isset($_GET["page"]) ? $_GET["page"] : "home");


if(!isset($_SESSION["user"]) && $page != PAGE_LOGIN) {
    redirect(PAGE_LOGIN, PAGE_LOGIN_LOGIN);
}

if($page == PAGE_ADMIN && !$_SESSION["user"]["admin"]) {
    $page = "home";
}

if(isset($_SESSION["error"])) {
    echo $_SESSION["error"];
    unset($_SESSION["error"]);
}


function is_home($page){
    return $page == PAGE_HOME;
}

function is_games($page) {
    switch($page) {
        case PAGE_GAMES:
        case PAGE_REGISTER_GAME:
        case PAGE_GAME_DETAILS:
            return true;
    }
    return false;
}

function is_users($page) {
    switch($page) {
        case PAGE_USERS:
        case PAGE_USER_DETAILS:
            return true;
    }
    return false;
}

function is_profile($page) {
    return $page == PAGE_PROFILE;
}
?>
<html>
<head>
    <title>NVSG - <?php echo $page; ?></title>
    <link rel="stylesheet" href="static/css/main_style.css" type="text/css">

    <?php
        if($page == PAGE_LOGIN) {
            echo "<link rel='stylesheet' href='static/css/login_style.css' type='text/css'>";
        }
    
    ?>
</head>
<body>
    <div>
        <ul class="navbar-main">
            <li class="main-menu-item"><a href="<?php echo menu_url(PAGE_HOME); ?>"><img src="static/img/nvsg_logo.png" alt="NVSG" id="img_logo"></a></li>
            <li class="main-menu-item"><a class="menu-link <?php echo is_home($page) ? "active-main" : ""; ?>" href="<?php echo menu_url(PAGE_HOME); ?>">Home</a></li>
            <li class="main-menu-item"><a class="menu-link <?php echo is_games($page) ? "active-main" : ""; ?>" href="<?php echo menu_url(PAGE_GAMES); ?>">Games</a></li>
            <li class="main-menu-item"><a class="menu-link <?php echo is_users($page) ? "active-main" : ""; ?>" href="<?php echo menu_url(PAGE_USERS); ?>">Users</a></li>
            <li class="main-menu-item" style="float: right;"><a class="menu-link <?php echo is_profile($page) ? "active-main" : ""; ?>" href="<?php echo menu_url(PAGE_PROFILE); ?>">Profile</a></li>
        </ul>
    </div>


    <div class="content">
    <?php
        
        include "include/pages/" . $page . ".php";
    ?>

    </div>
</body>
</html>