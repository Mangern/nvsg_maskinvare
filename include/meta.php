<?php 

/*
CONSTANTS
*/

// Urls
const BASE_URL = "http://localhost/nvsg_maskinvare/index.php";

// Pages
const PAGE_NO_SUB = "";
const PAGE_LOGIN = "login";
const PAGE_HOME = "home";
const PAGE_GAMES = "games";
const PAGE_USERS = "users";
const PAGE_PROFILE = "profile";
const PAGE_GAME_DETAILS = "game_details";
const PAGE_REGISTER_GAME = "register_game";
const PAGE_USER_DETAILS = "user_details";
const PAGE_ADMIN = "admin";

// Sub-pages
const PAGE_LOGIN_LOGIN = "login_login";
const PAGE_LOGIN_REGISTER = "login_register";

const PAGE_PROFILE_SETTINGS = "profile_settings";
const PAGE_PROFILE_MACHINES = "profile_machines";
const PAGE_PROFILE_ADD_MACHINE ="profile_add_machine";
const PAGE_PROFILE_GAMES = "profile_games";
const PAGE_PROFILE_ADD_GAME = "profile_add_game";
const PAGE_PROFILE_ACCOUNTS = "profile_accounts";

const PAGE_USER_DETAILS_PROFILE = "user_details_profile";
const PAGE_USER_DETAILS_MACHINES = "user_details_machines";
const PAGE_USER_DETAILS_GAMES = "user_details_games";

const PAGE_ADMIN_PLATFORMS = "admin_platforms";

// Other
const ID_PLATFORM_PC = 1;


/*
HELPER FUNCTIONS
*/

// Wrapper for header()
function redirect($page = "", $sub_page = "") {
    if($page == "")header("Location: " . BASE_URL);
    $suffix = "";
    if($sub_page != "") {
        $suffix = "&sub_page=" . $sub_page;
    }
    header("Location: " . BASE_URL . "?page=" . $page . $suffix);
}

function menu_url($page) {
    return BASE_URL . "?page=" . $page;
}

function sub_url($parent_page, $page) {
    return BASE_URL . "?page=" . $parent_page . "&sub_page=" . $page;
}
?>