<?php 
include 'include/meta.php';

session_start();

$page = (isset($_GET["page"]) ? $_GET["page"] : "home");

if(!isset($_SESSION["user"]) && $page != PAGE_LOGIN && $page != PAGE_REGISTER) {
    header("Location: " . BASE_URL . "?page=" . PAGE_LOGIN);
}

if(isset($_SESSION["error"])) {
    echo $_SESSION["error"];
    unset($_SESSION["error"]);
}


function menu_url($name) {
    return BASE_URL . "?page=" . $name;
}

?>
<html>
<head>
    <title>NVSG - <?php echo $page; ?></title>
</head>
<body>
    <div>
        <ul>
            <li><a href="<?php echo menu_url(PAGE_HOME); ?>">Home</a></li>
            <li><a href="<?php echo menu_url(PAGE_GAMES); ?>">Games</a></li>
            <li><a href="<?php echo menu_url(PAGE_USERS); ?>">Users</a></li>
            <li><a href="<?php echo menu_url(PAGE_PROFILE); ?>">Profile</a></li>
            <li><a href="form_processors/logout.php">Log out</a></li>
        </ul>
    </div>


    <div>
    <?php 
        include "include/pages/" . $page . ".php";
    ?>

    </div>
</body>
</html>