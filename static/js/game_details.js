function on_platform_select() {
    var table = document.getElementById("tbody_game_requirements");
    table.innerHTML = ""; // Clear table

    var selected_platform = document.getElementById("input_requirement_platform").value;

    console.log(platforms);
    

    var selected_idx;
    for(idx in platforms) {
        if(platforms[idx].id == selected_platform) {
            selected_idx = idx;
            var table_row = document.createElement("tr");
            // id and stuff

            var machine = platforms[idx].minimum_machine;
            for (let [key, value] of Object.entries(machine)) {
                var data = document.createElement("td");
                data.innerText = value;

                if(key == "ram" || key == "storage_space") {
                    data.innerText += " GB";
                }

                table_row.appendChild(data);
            }

            table.appendChild(table_row);
        }
    }

    var can_play = platforms[selected_idx].user_can_play;
    var selected_name = platforms[selected_idx].name;

    var text_field = document.getElementById("p_can_play_text");
    if(can_play) {
        text_field.innerText = "You can play " + game_title + " on " + selected_name;
    }
    else {
        text_field.innerText = "You can not play " + game_title + " on " + selected_name;
    }
}
on_platform_select();