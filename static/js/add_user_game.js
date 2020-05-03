function on_select_game() {
    var selected_game_id = document.getElementById("select_game").value;

    for(idx in games) {
        if(games[idx].id_game == selected_game_id) {
            // Append platform options to platform select
            var platform_select = document.getElementById("select_platform");

            platform_select.innerHTML = "";

            var platforms = games[idx].platforms;
            for(pidx in platforms) {
                var option = document.createElement("option");

                option.value = platforms[pidx].id;
                option.innerText = platforms[pidx].name;

                platform_select.appendChild(option);
            }
        }
    }
}

on_select_game();