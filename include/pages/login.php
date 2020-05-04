<?php 
$page = (isset($_GET["sub_page"]) ? $_GET["sub_page"] : PAGE_LOGIN_LOGIN);


function is_login() {
    global $page;
    return $page == PAGE_LOGIN_LOGIN;
}


function is_register() {
    global $page;
    return $page == PAGE_LOGIN_REGISTER;
}
?>


<div id="container_home">
<div class="bg-image"></div>

<div class="bg-text">
<div class="login-content">
<?php 

include 'include/pages/sub_pages/' . $page . '.php';
?>
</div>
