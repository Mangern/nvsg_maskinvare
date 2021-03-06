// cpu_list: array of {id_cpu, name}
// gpu_list: array of {id_gpu, name}
// platforms: array of {id_platform, name, minimum_machine} objects

var platform_counter = 0;
const DISPLAY_TYPE = "block";

function create_input_element(placeholder, name, type) {
    var element = document.createElement("input");
    element.id = "input_" + name;
    element.type = type;
    element.placeholder = placeholder;
    element.name = name;
    element.style.display = "none";
    return element;
}

function on_platform_select(id, show, name) {

    
    document.getElementById("input_storage_space_" + id).style.display = DISPLAY_TYPE;
    if(show) {
        document.getElementById("input_ram_" + id).style.display = DISPLAY_TYPE;
        document.getElementById("select_cpu_" + id).style.display = DISPLAY_TYPE;
        document.getElementById("select_gpu_" + id).style.display = DISPLAY_TYPE;
        
    }
    else {
        document.getElementById("input_ram_" + id).style.display = "none";
        document.getElementById("select_cpu_" + id).style.display = "none";
        document.getElementById("select_gpu_" + id).style.display = "none";
    }

}

function make_label(text, input_name) {
    var label = document.createElement("label");
    label.for = input_name;
    label.innerText = text;
    return label;
}

function add_platform() {
    var mini_form = document.createElement("div");
    mini_form.className += "spaced";
    


    var select_id = "select_platform_" + platform_counter;
    var select_name = "platform_" + platform_counter;
    var label = document.createElement("label");
    label.for = select_name;

    label.appendChild(document.createTextNode("Platform"));

    var select = document.createElement("select");
    select.id = select_id;
    select.name = select_name;

    var option = document.createElement("option");
    option.id = -1;
    option.innerText = "Not selected";

    select.appendChild(option);

    var option_cnt = 0;
    for(idx in platforms) {

        var id = platforms[idx].id_platform;
        var name = platforms[idx].name;

        let unavailable = false;
        for(var i = 0; i < platform_counter; ++i) {
            if(document.getElementById("select_platform_" + i).value == name) {
                unavailable = true;
                break;
            }
        }
       
        if(unavailable)continue;

        let option = document.createElement("option");
        option.id = "option_" + option_cnt + "_" + name;
        option.value = id;
        option.appendChild(document.createTextNode(name))
        select.appendChild(option);
    }

    select.onchange = function(evt) {
        var id = evt.target.id.substring(16);
        console.log(evt.target.value);
        if(evt.target.value == ID_PLATFORM_PC) {
            on_platform_select(id, true, evt.target.value);
        }
        else {
            on_platform_select(id, false, evt.target.value);
        }
    }

    mini_form.appendChild(label);
    mini_form.appendChild(select);

    var req_label = document.createElement("label");
    req_label.innerText = "Requirements";

    mini_form.appendChild(req_label);


    var sspace_name = "storage_space_" + platform_counter;
    var sspace_elem = create_input_element("Storage Space", sspace_name, "number");

    mini_form.appendChild(sspace_elem);



    mini_form.appendChild(create_input_element("RAM", "ram_" + platform_counter, "number"));

    var cpu_select = document.createElement("select");
    cpu_select.id = "select_cpu_" + platform_counter;
    cpu_select.name = "cpu_" + platform_counter;
    cpu_select.style.display = "none";

    for(idx in cpu_list) {
        let id = cpu_list[idx].id_cpu;
        let name = cpu_list[idx].name;

        let option = document.createElement("option");
        option.id = id;
        option.value = id;
        option.innerText = name;
        cpu_select.appendChild(option);
    }


    mini_form.appendChild(cpu_select);

    var gpu_select = document.createElement("select");
    gpu_select.id = "select_gpu_" + platform_counter;
    gpu_select.name = "gpu_" + platform_counter;
    gpu_select.style.display = "none";

    for(idx in gpu_list) {
        let id = gpu_list[idx].id_gpu;
        let name = gpu_list[idx].name;

        let option = document.createElement("option");
        option.id = id;
        option.value = id;
        option.innerText = name;
        gpu_select.appendChild(option);
    }


    mini_form.appendChild(gpu_select);


    document.getElementById("container_dynamic_inputs").appendChild(mini_form);
    
    platform_counter++;

    document.getElementById("input_submit").style.visibility = "visible";

    document.getElementById("input_num_platforms").value = platform_counter;

    if(platform_counter == platforms.length) {
        document.getElementById("button_add_platform").style.display = "none";
    }


}