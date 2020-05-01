<?php
class API {
    private $conn;

    // Helpers
    private function db_error_response($location = "", $error_info = "") {
        return array("error" => true, "error_msg" => "Failed to query to database on " . $location . ($error_info == "" ? "" : ": " . $error_info));
    }

    private function fetch_all($table) {
        $result = $this->conn->query("SELECT * FROM $table");



        $response = array(
            "error" => false,
            "result" => array()
        );

        while($row = $result->fetch_assoc()) {
            array_push($response["result"], $row);
        }
        return $response;
    }
    

    function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "nvsg_maskinvare");
    }

    function __destruct() {
        $this->conn->close();
    }

    

    // Modifiers
    function register($email, $password, $first_name, $last_name) {
        $response = array("error" => false);

        // check if user with email exists
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);

        if(!$stmt->execute()) {
            return $this->db_error_response("register/check email");
        }

        if($stmt->get_result()->num_rows != 0) { 
            $response["error"] = true;
            $response["error_msg"] = "User with email " . $email . " already exists.";
            return $response;
        }

        // register user
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO user (email, password_hash, first_name, last_name) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $hash, $first_name, $last_name);
        
        if(!$stmt->execute()) {
            return $this->db_error_response("register/insert user");
        }



        $response["result"] = array(
            "id" => $stmt->insert_id,
            "email" => $email,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "nickname" => NULL
        );
        return $response;
    }

    function insert_machine($user_id, $name, $ram, $cpu, $gpu, $storage_space, $platform_id) {
        // Insert id
        $stmt = $this->conn->prepare("INSERT INTO machine (name, ram, id_cpu, id_gpu, storage_space, id_platform) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiiii", $name, $ram, $cpu, $gpu, $storage_space, $platform_id);

        if(!$stmt->execute()) {
            return $this->db_error_response("insert machine", $stmt->error);
        }

        $machine_id = $stmt->insert_id;

        // Bind machine to user
        
        $stmt = $this->conn->prepare("INSERT INTO user_has_machine (id_user, id_machine) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $machine_id);

        if(!$stmt->execute()) {
            return $this->db_error_response("add machine to user");
        }

        return array(
            "error" => false,
            "result" => array(
                "id" => $machine_id
            )
        );
    }

    function insert_default_machine($user_id, $platform_id) {
        $sql = <<<SQL
            INSERT INTO user_has_machine (id_user, id_machine) VALUES (
                ?,
                (SELECT id_default_machine FROM platform WHERE id_platform = ?)
            )
        SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $platform_id);

        if(!$stmt->execute()) {
            return $this->db_error_response("insert machine for platform $platform_id");
        }

        return array("error" => false);
    }

    function delete_machine($user_id, $machine_id) {
        // First, delete from user-machine table

        $stmt = $this->conn->prepare("DELETE FROM user_has_machine WHERE id_user = ? AND id_machine = ?");
        $stmt->bind_param("ii", $user_id, $machine_id);

        if(!$stmt->execute()) {
            return $this->db_error_response("delete user-machine relationship");
        }

        // Check if machine is still in use
        $stmt = $this->conn->prepare("SELECT * FROM user_has_machine WHERE id_machine = ?");
        $stmt->bind_param("i", $machine_id);

        if(!$stmt->execute()) {
            return $this->db_error_response("delete machine / check machine usage");
        }

        $response = array("error" => false, "result" => array("degree" => "partial"));

        if($stmt->get_result()->num_rows != 0) {

            
            return $response;
        }

        // Check if machine is a default machine
        $stmt = $this->conn->prepare("SELECT * FROM platform WHERE id_default_machine = ?");

        $stmt->bind_param("i", $machine_id);

        if(!$stmt->execute()) {
            return $this->db_error_response("delete machine / check default machine");
        }

        if($stmt->get_result()->num_rows != 0) {

            return $response;
        }

        // Delete machine
        $stmt = $this->conn->prepare("DELETE FROM machine WHERE id_machine = ?");
        $stmt->bind_param("i", $machine_id);

        if(!$stmt->execute()) {
            return $this->db_error_response("delete machine / delete machine");
        }

        
        $response["result"]["degree"] = "total";
        return $response;
    }

    function update_user($user_id, $email, $first_name, $last_name, $nickname) {
        $sql = <<<SQL
            UPDATE user SET
                email = ?,
                first_name = ?,
                last_name = ?,
                nickname = ?
            WHERE id_user = ?
        SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $email, $first_name, $last_name, $nickname, $user_id);

        if(!$stmt->execute()) {
            return $this->db_error_response("update user");
        }

        return array(
            "error" => false,
            "result" => array(
                "id" => $user_id,
                "email" => $email,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "nickname" => $nickname
            )
        );

    }

    // Accessers
    

    function verify_user($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);

        if(!$stmt->execute()) {
            return $this->db_error_response("login/find user");
        }

        $res = $stmt->get_result();
        if($res->num_rows == 0) {
            $response["error"] = true;
            $response["error_msg"] = "No user with email " . $email . " exists.";
            return $response; 
        }

        $result = $res->fetch_assoc();

        $hash = $result["password_hash"];

        if(!password_verify($password, $hash)) {
            $response["error"] = true;
            $response["error_msg"] = "Incorrect password.";
            return $response;
        }
        $response["error"] = false;
        $response["result"] = array(
            "id" => $result["id_user"],
            "email" => $result["email"],
            "first_name" => $result["first_name"],
            "last_name" => $result["last_name"],
            "nickname" => $result["nickname"]
        );
        return $response;
    }

    function fetch_platforms() {
        return $this->fetch_all("platform");
    }

    function fetch_gpu() {
        return $this->fetch_all("gpu");
    }

    function fetch_cpu() {
        return $this->fetch_all("cpu");
    }

    function fetch_machines($user_id) {
        $sql = <<<SQL
            SELECT * FROM 
                    machine 
                INNER JOIN 
                    user_has_machine 
                ON machine.id_machine = user_has_machine.id_machine
                INNER JOIN
                    (SELECT id_cpu, name as cpu_name FROM
                        cpu
                    ) AS cpu_derived
                ON machine.id_cpu = cpu_derived.id_cpu
                INNER JOIN
                    (SELECT id_gpu, name as gpu_name FROM
                        gpu
                    ) AS gpu_derived
                ON machine.id_gpu = gpu_derived.id_gpu
            WHERE user_has_machine.id_user = ?
        SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if(!$stmt->execute()) {
            return $this->db_error_response("fetch machines");
        }

        $res = $stmt->get_result();
        $result = array();

        while($row = $res->fetch_assoc()) {
            array_push($result, $row);
        }

        return array(
            "error" => false,
            "result" => $result
        );
    }

    function fetch_games_platforms() {
        $sql = <<<SQL
            SELECT * FROM 
                game 
            JOIN 
                game_has_platform ON game.id_game = game_has_platform.id_game 
            JOIN 
                platform ON platform.id_platform = game_has_platform.id_platform
            SQL;

        $res = $this->conn->query($sql);

        if(!$res)return $this->db_error_response("fetch games and platforms");

        $response = array("error" => false, "result" => array());



        $game_platform = array();

        while($row = $res->fetch_assoc()) {
            $game_id = $row["id_game"];
            $game_title = $row["title"];
            $platform = $row["name"];

            if(array_key_exists($game_id, $game_platform)) {
                array_push($game_platform[$game_id]["platforms"], $platform);
            }
            else {
                $game_platform[$game_id]["game_title"] = $game_title;
                $game_platform[$game_id]["platforms"] = array($platform);

            }
        }

        foreach($game_platform as $game_id => $platform_title_array) {
            array_push($response["result"], array(
                "id_game" => $game_id, 
                "game_title" => $platform_title_array["game_title"], 
                "platforms" => $platform_title_array["platforms"]));
        }
        return $response;
    }

    // Returns true iff machine 1 has better specs than machine 2 
    function compare_machines($id_machine_1, $id_machine_2) {
        // Fetch machine 1
        $sql = <<<SQL
            SELECT * FROM 
                    machine 
                INNER JOIN
                    (SELECT id_cpu, name as cpu_name, score as cpu_score FROM
                        cpu
                    ) AS cpu_derived
                ON machine.id_cpu = cpu_derived.id_cpu
                INNER JOIN
                    (SELECT id_gpu, name as gpu_name, score as gpu_score FROM
                        gpu
                    ) AS gpu_derived
                ON machine.id_gpu = gpu_derived.id_gpu
            WHERE id_machine = ?
        SQL;

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $id_machine_1);
        if(!$stmt->execute()) {
            return $this->db_error_response("compare machines / fetch machine 1");
        }

        $machine_1 = $stmt->get_result()->fetch_assoc();

        $stmt->bind_param("i", $id_machine_2);
        if(!$stmt->execute()) {
            return $this->db_error_response("compare machines / fetch machine 2");
        }

        $machine_2 = $stmt->get_result()->fetch_assoc();

        $ram_ok = $machine_1["ram"] >= $machine_2["ram"];
        $cpu_ok = $machine_1["cpu_score"] >= $machine_2["cpu_score"];
        $gpu_ok = $machine_1["gpu_score"] >= $machine_2["gpu_score"];
        $storage_space_ok = $machine_1["storage_space"] >= $machine_2["storage_space"];

        
        $response = array(
            "error" => false,
            "result" => array(
                "verdict" => ($ram_ok && $cpu_ok && $gpu_ok && $storage_space_ok),
                "ram_ok" => $ram_ok,
                "cpu_ok" => $cpu_ok,
                "gpu_ok" => $gpu_ok,
                "storage_space_ok" => $storage_space_ok
            )
        );
        return $response;
    }

    // Returns true iff the user has a machine that is able to play given game
    function user_can_play_game($user_id, $game_id) {
        $sql = <<<SQL
            SELECT * FROM 
                game_has_platform 
            INNER JOIN 
                (SELECT machine.* FROM 
                        machine 
                    INNER JOIN 
                        user_has_machine
                    ON machine.id_machine = user_has_machine.id_machine
                WHERE id_user = ?) AS user_machine
            ON user_machine.id_platform = game_has_platform.id_platform
            INNER JOIN
                (SELECT name AS platform_name, id_platform FROM platform) as platform_derived
            ON game_has_platform.id_platform = platform_derived.id_platform
        WHERE id_game = ?
        SQL;

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("ii", $user_id, $game_id);
        if(!$stmt->execute())return $this->db_error_response("user can play game / fetch game platform machines");

        $res = $stmt->get_result();

        $response = array(
            "error" => false,
            "result" => array()
        );

        while($row = $res->fetch_assoc()) {
            $user_machine = $row["id_machine"];
            $game_machine = $row["id_minimum_machine"];

            $can_play_response = $this->compare_machines($user_machine, $game_machine);

            if($can_play_response["error"])return $can_play_response;

            $can_play = $can_play_response["result"];

            $can_play["id_machine"] = $user_machine;
            $can_play["platform"] = $row["platform_name"];
            array_push($response["result"], $can_play);
        }

        return $response;
    }
}

$api_handle = new API();
?>