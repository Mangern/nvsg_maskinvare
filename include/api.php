<?php
class API {
    private $conn;

    // Helpers
    private function db_error_response($location = "", $error_info = "") {
        return array("error" => true, "error_msg" => "Unknown error occurred in " . $location . ($error_info == "" ? "" : ": " . $error_info));
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
            "nickname" => NULL,
            "admin" => 0
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

    // Inserts the default machine of given platform to given user
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

    function insert_user_game($user_id, $game_id, $platform_id) {
        $stmt = $this->conn->prepare("INSERT INTO user_has_game_on_platform (id_user, id_game, id_platform) VALUES (?, ? ,?)");
        $stmt->bind_param("iii", $user_id, $game_id, $platform_id);

        if(!$stmt->execute()) {
            return $this->db_error_response("insert user game");
        }
        return array("error" => false, "result" => $stmt->insert_id);
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

        // Check if machine is a minimum machine
        $stmt = $this->conn->prepare("SELECT * FROM game_has_platform WHERE id_minimum_machine = ?");
        $stmt->bind_param("i", $machine_id);

        if(!$stmt->execute()) {
            return $this->db_error_response("delete machine / check minimum machine");
        }

        if($stmt->get_result()->num_rows != 0){
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

    function delete_user_game($user_id, $game_id, $platform_id) {
        $stmt = $this->conn->prepare("DELETE FROM user_has_game_on_platform WHERE id_user = ? AND id_game = ? AND id_platform = ?");
        $stmt->bind_param("iii", $user_id, $game_id, $platform_id);

        if(!$stmt->execute()) {
            return $this->db_error_response("delete user game");
        }
        return array("error" => false);
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

    function register_game($title) {
        $stmt = $this->conn->prepare("SELECT * FROM game WHERE title = ?");
        $stmt->bind_param("s", $title);

        if(!$stmt->execute()) return $this->db_error_response("register game / check game exists");

        $res = $stmt->get_result();
        if($res->num_rows != 0) {
            return array("error" => false, "result" => array("already_exists" => true, "id" => $res->fetch_assoc()["id_game"]));
        }

        $stmt = $this->conn->prepare("INSERT INTO game (title) VALUES (?)");
        $stmt->bind_param("s", $title);

        if(!$stmt->execute()) return $this->db_error_response("register game / insert game");

        $response = array("error" => false, "result" => array("already_exists" => false, "id" => $stmt->insert_id));
        return $response;
    }

    function register_platform_to_game($game_id, $game_title, $platform_id, $requirements, $compatibility) {
        $machine = array(
            "name" => $game_title . " minimum machine " . $platform_id,
            "ram" => $requirements["ram"],
            "cpu_id" => $requirements["cpu_id"],
            "gpu_id" => $requirements["gpu_id"],
            "storage_space" => $requirements["storage_space"]
        );
        if($requirements["ram"] == -1) {
            // Collect platform specs
            $response = $this->fetch_default_machine($platform_id);
            if($response["error"])return $response;

            $default_machine = $response["result"];

            $machine["ram"] = $default_machine["ram"];
            $machine["cpu_id"] = $default_machine["id_cpu"];
            $machine["gpu_id"] = $default_machine["id_gpu"];
        }
        
        // insert machine
        $sql = <<<SQL
            INSERT INTO
                machine (name, ram, id_cpu, id_gpu, storage_space, id_platform)
            VALUES
                (?, ?, ?, ?, ?, ?)
        SQL;
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siiiii", 
            $machine["name"], 
            $machine["ram"],
            $machine["cpu_id"],
            $machine["gpu_id"],
            $machine["storage_space"],
            $platform_id
        );

        if(!$stmt->execute())return $this->db_error_response("register platform to game / insert minimum machine", $stmt->error);

        $machine_id = $stmt->insert_id;

        // register relationship
        $stmt = $this->conn->prepare("INSERT INTO game_has_platform (id_game, id_platform, compatibility, id_minimum_machine) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $game_id, $platform_id, $compatibility, $machine_id);

        if(!$stmt->execute())return $this->db_error_response("register platform to game");

        return array("error" => false);
    }

    function add_account($user_id, $service_id, $account_tag) {
        $stmt = $this->conn->prepare("INSERT INTO user_has_account (id_user, id_service, account_tag) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $service_id, $account_tag);

        if(!$stmt->execute()) {
            return $this->db_error_response("add account");
        }

        return array("error" => false, "result" => array("id" => $stmt->insert_id));
    }

    function update_account($user_id, $service_id, $account_tag) {
        $sql = <<<SQL
            UPDATE user_has_account SET
                account_tag = ?
            WHERE id_user = ? AND id_service = ?
        SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $account_tag, $user_id, $service_id);

        if(!$stmt->execute())return $this->db_error_response("update account");

        return array("error" => false);
    }


    function insert_platform($name) {
        $sql = <<<SQL
            INSERT INTO
                platform (name)
            VALUES
                (?)
        SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $name);

        if(!$stmt->execute())return $this->db_error_response("insert platform");

        return array("error" => false);
    }

    function register_default_machine($platform_id, $platform_name, $ram, $storage_space, $cpu_name, $gpu_name) {
        // insert gpu and cpu
        $stmt = $this->conn->prepare("INSERT INTO cpu (name, score) VALUES (?, 0)");
        $stmt->bind_param("s", $cpu_name);

        if(!$stmt->execute())return $this->db_error_response("register default machine / insert cpu", $stmt->error);

        $cpu_id = $stmt->insert_id;

        $stmt = $this->conn->prepare("INSERT INTO gpu (name, score) VALUES (?, 0)");
        $stmt->bind_param("s", $gpu_name);

        if(!$stmt->execute())return $this->db_error_response("register default machine / insert gpu", $stmt->error);

        $gpu_id = $stmt->insert_id;

        // Insert default machine
        $sql = <<<SQL
            INSERT INTO machine (
                name,
                ram,
                id_cpu,
                id_gpu,
                storage_space,
                id_platform
            )
            VALUES (
                ?,?,?,?,?,?
            )
        SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siiiii", $platform_name, $ram, $cpu_id, $gpu_id, $storage_space, $platform_id);

        if(!$stmt->execute())return $this->db_error_response("register default machine", $stmt->error);

        // Bind to platform
        $machine_id = $stmt->insert_id;

        $stmt = $this->conn->prepare("UPDATE platform SET id_default_machine = ? WHERE id_platform = ?");
        $stmt->bind_param("ii", $machine_id, $platform_id);

        if(!$stmt->execute())return $this->db_error_response("register default machine / bind to platform", $stmt->error);

        return array("error" => false);
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
            "nickname" => $result["nickname"],
            "admin" => $result["admin"]
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

    function fetch_user() {
        return $this->fetch_all("user");
    }

    function fetch_third_party_service() {
        return $this->fetch_all("third_party_service");
    }

    // Returns response containing array of machines
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

    function fetch_default_machine($platform_id) {
        $sql = <<<SQL
            SELECT * FROM 
                    platform 
                INNER JOIN 
                    machine 
                ON machine.id_machine = id_default_machine 
            WHERE platform.id_platform = ?
        SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $platform_id);

        if(!$stmt->execute())return $this->db_error_response("fetch_default_machine");
        $result = $stmt->get_result();

        if($result->num_rows == 0) {
            return array("error" => true, "error_msg" => "Platform $platform_id has no default machine");
        }

        $response = array("error" => false, "result" => $result->fetch_assoc());
        return $response;
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
            $platform_id = $row["id_platform"];
            $platform = $row["name"];

            $platform_row = array("id" => $platform_id, "name" => $platform);

            if(array_key_exists($game_id, $game_platform)) {
                array_push($game_platform[$game_id]["platforms"], $platform_row);
            }
            else {
                $game_platform[$game_id]["game_title"] = $game_title;
                $game_platform[$game_id]["platforms"] = array($platform_row);

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

    function fetch_user_games($user_id) {
        $sql = <<<SQL
            SELECT * FROM 
                    user_has_game_on_platform
                INNER JOIN
                    game
                ON user_has_game_on_platform.id_game = game.id_game
                INNER JOIN
                    platform
                ON user_has_game_on_platform.id_platform = platform.id_platform
            WHERE id_user = ?
            ORDER BY user_has_game_on_platform.id_game
        SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if(!$stmt->execute())return $this->db_error_response("fetch user games", $stmt->error);
        
        $res = $stmt->get_result();

        $response = array(
            "error" => false,
            "result" => array()
        );

        while($row = $res->fetch_assoc()) {
            array_push($response["result"], $row);
        }

        return $response;
    }

    // Returns response where response->result->verdict is true iff machine 1 has better specs than machine 2 
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

    // Returns either true or false
    private function user_can_play_game_on_platform($user_id, $game_id, $platform_id) {
        $stmt = $this->conn->prepare("SELECT * FROM game_has_platform WHERE id_game = ? AND id_platform = ?");
        $stmt->bind_param("ii", $game_id, $platform_id);

        if(!$stmt->execute())return false;

        $game_machine_id = $stmt->get_result()->fetch_assoc()["id_minimum_machine"];

        $response = $this->fetch_machines($user_id);
        if($response["error"])return false;

        $machines = $response["result"];

        foreach($machines as $row) {
            if($row["id_platform"] != $platform_id)continue;
            $response = $this->compare_machines($row["id_machine"], $game_machine_id);
            if(!$response["error"]) {
                if($response["result"]["verdict"] == true)return true;
            }
        }
        return false;
    }

    private function fetch_game_users($game_id) {
        $sql = <<<SQL
            SELECT * FROM 
                    user_has_game_on_platform
                JOIN
                    (SELECT * FROM user) as user_derived
                ON user_has_game_on_platform.id_user = user_derived.id_user
                JOIN 
                    platform
                ON user_has_game_on_platform.id_platform = platform.id_platform
            WHERE id_game = ?
            ORDER BY user_derived.id_user
        SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $game_id);

        if(!$stmt->execute())return $this->db_error_response("fetch game users");

        $res = $stmt->get_result();

        $response = array("error" => false, "result" => array());

        while($row = $res->fetch_assoc()) {
            array_push($response["result"], $row);
        }
        return $response;
    }

    function fetch_game_details($game_id, $user_id) {
        $sql = <<<SQL
            SELECT * FROM 
                game_has_platform 
            JOIN 
                (SELECT * FROM
                    machine
                INNER JOIN
                    (SELECT id_cpu AS derived_cpu, name AS cpu_name FROM cpu) AS cpu_derived
                ON machine.id_cpu = cpu_derived.derived_cpu
                INNER JOIN
                    (SELECT id_gpu AS derived_gpu, name AS gpu_name FROM gpu) AS gpu_derived
                ON machine.id_gpu = gpu_derived.derived_gpu)
                AS machine_derived
            ON game_has_platform.id_minimum_machine = machine_derived.id_machine
            JOIN
                game
            ON game.id_game = game_has_platform.id_game
            JOIN
                (SELECT id_platform, name AS platform_name FROM platform) AS platform_derived
            ON game_has_platform.id_platform = platform_derived.id_platform
        WHERE game.id_game = ?
        SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $game_id);

        if(!$stmt->execute()) {
            return $this->db_error_response("fetch game details", $stmt->error);
        }

        $res = $stmt->get_result();

        $response = array("error" => false, "result" => array(
            "title" => "",
            "platforms" => array()
        ));
        
        while($row = $res->fetch_assoc()) {
            $response["result"]["title"] = $row["title"];

            $platform_entry = array("id" => $row["id_platform"],
                                    "name" => $row["platform_name"],
                                    "minimum_machine" => array());

            // The order of the data is kind of important
            $platform_entry["minimum_machine"]["storage_space"] = $row["storage_space"];
            $platform_entry["minimum_machine"]["ram"] = $row["ram"];
            $platform_entry["minimum_machine"]["cpu"] = $row["cpu_name"];
            $platform_entry["minimum_machine"]["gpu"] = $row["gpu_name"];

            $can_play = $this->user_can_play_game_on_platform($user_id, $game_id, $row["id_platform"]);
            $platform_entry["user_can_play"] = $can_play;

            array_push($response["result"]["platforms"], $platform_entry);
        }

        $users_response = $this->fetch_game_users($game_id);
        if($users_response["error"])return $users_response;

        $response["result"]["users"] = $users_response["result"];
        return $response;
    }

    function fetch_user_details($user_id) {
        $sql = <<<SQL
            SELECT
                id_user AS id,
                email,
                first_name,
                last_name,
                nickname
            FROM
                    user
            WHERE id_user = ?
        SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if(!$stmt->execute())return $this->db_error_response("fetch user details");
        
        $user = $stmt->get_result()->fetch_assoc();
        return array("error" => false, "result" => $user);
    }

    function fetch_user_accounts($user_id) {
        $sql = <<<SQL
            SELECT * FROM 
                    user_has_account
                JOIN
                    third_party_service
                ON user_has_account.id_service = third_party_service.id_service
            WHERE id_user = ?
        SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if(!$stmt->execute())return $this->db_error_response("fetch user accounts");

        $accounts = array();

        $res = $stmt->get_result();

        while($row = $res->fetch_assoc()) {
            array_push($accounts, $row);
        }

        return array("error" => false, "result" => $accounts);
    }
}

$api_handle = new API();
?>