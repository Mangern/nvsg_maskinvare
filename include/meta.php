<?php 

/*
CONSTANTS
*/

// Urls
const BASE_URL = "http://localhost/nvsg_maskinvare/index.php";

// Pages
const PAGE_LOGIN = "login";
const PAGE_REGISTER = "register";
const PAGE_HOME = "home";
const PAGE_PROFILE = "profile";
const PAGE_GAMES = "games";
const PAGE_ADD_MACHINE ="add_machine";
const PAGE_ADD_GAME = "add_user_game";
const PAGE_GAME_DETAILS = "game_details";
const PAGE_REGISTER_GAME = "register_game";
const PAGE_USER_DETAILS = "user_details";

// Other
const ID_PLATFORM_PC = 1;


/*
HELPER FUNCTIONS
*/

function redirect($page = "") {
    if($page == "")header("Location: " . BASE_URL);
    else header("Location: " . BASE_URL . "?page=" . $page);
}
?>