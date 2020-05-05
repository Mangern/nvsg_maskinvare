<?php 
include 'include/api.php';

$page = (isset($_GET["sub_page"]) ? $_GET["sub_page"] : PAGE_ADMIN_PLATFORMS);

function is_platforms() {
    global $page;
    return $page == PAGE_ADMIN_PLATFORMS;
}
?>

<ul class="navbar-left">
    <li class="sub-menu-item"><a class="<?php if(is_platforms())echo "active-left"; ?>" href="<?php echo sub_url(PAGE_ADMIN, PAGE_ADMIN_PLATFORMS); ?>">Platforms</a></li>
</ul> 

<div class="inner-content">
<?php 

include 'include/pages/sub_pages/' . $page . '.php';
?>
</div>