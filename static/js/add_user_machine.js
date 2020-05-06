function on_select_platform() {
    var select = document.getElementById("select_platform");

    var container = document.getElementById("container-pc-specs");

    if(select.value == ID_PLATFORM_PC) {
        container.style.visibility = "visible";
        container.style.height = "auto";
    }
    else {
        container.style.visibility = "hidden";
        container.style.height = "0px";
    }
}