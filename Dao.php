<?php
// Dao.php
// class for saving and getting comments from MySQL
require_once 'KLogger.php';
class Dao {

    private $logger = null;
    private $host = "localhost";
    private $db = "afilament";
    private $user = "afilament";
    private $pass = "BoiseState123!";

//    private $logger = null;
//    private $host = "us-cdbr-east-05.cleardb.net";
//    private $db = "heroku_715721257573706";
//    private $user = "b2123b4f7db64f";
//    private $pass = "17e40ade";

    public function __construct() {
        $this->logger = new KLogger ( "log.txt" , KLogger::WARN );
    }

    public function getConnection () {
        $this->logger->LogDebug("getting a connection...");
        try {
            return
                new PDO("mysql:host={$this->host};dbname={$this->db}", $this->user, $this->pass);
        } catch (Exception $e) {
            $this->logger->LogFatal("The database exploded " . print_r($e,1));
            exit;
        }
    }

    public function addNewUser($email, $pass) {
        $this->logger->LogInfo("new user {$email} was created");
        $conn = $this->getConnection();
        $createUser = "INSERT INTO users (email, password) values (:email, :pass);";
        $q = $conn->prepare($createUser);
        $q->bindParam(":email", $email);
        $q->bindParam(":pass", $pass);
        $q->execute();

    }

    public function getUsers() {
        $conn = $this->getConnection();
        return $conn->query("SELECT * FROM users");
    }

    public function addNewCell($x, $y, $z, $nuc_channel, $actin_channel, $imagePath, $email, $original_filename) {
        $this->logger->LogInfo("new cell {$imagePath} was registered in the table");
        $conn = $this->getConnection();
        $addCell = "INSERT INTO cells (email,
                                            img_original_name,
                                            img_path,
                                            nucleus_channel,
                                            actin_channel,
                                            x_resolution,
                                            y_resolution,
                                            z_resolution)     
                                    values (:email,
                                            :img_original_name,
                                            :img_path,
                                            :nucleus_channel,
                                            :actin_channel,
                                            :x_resolution,
                                            :y_resolution,
                                            :z_resolution);";
        $q = $conn->prepare($addCell);
        $q->bindParam(":email", $email);
        $q->bindParam(":img_original_name", $original_filename);
        $q->bindParam(":img_path", $imagePath);
        $q->bindParam(":nucleus_channel", $nuc_channel);
        $q->bindParam(":actin_channel", $actin_channel);
        $q->bindParam(":x_resolution", $x);
        $q->bindParam(":y_resolution", $y);
        $q->bindParam(":z_resolution", $z);
        $q->execute();
        return $conn->query("SELECT * FROM cells
                                    WHERE img_path = '$imagePath'");
    }

    public function addCellAnalytics($cell_id, $nucleus_volume, $total_fiber_num, $total_fiber_volume, $total_fiber_length, $actin_stat_path) {
        $conn = $this->getConnection();
        $addCellAnalytics = "INSERT INTO analysed_cells (cell_id,
                                                        nucleus_volume,
                                                        total_fiber_num,
                                                        total_fiber_volume,
                                                        total_fiber_length,
                                                        actin_stat_path)     
                                                values (:cell_id,
                                                        :nucleus_volume,
                                                        :total_fiber_num,
                                                        :total_fiber_volume,
                                                        :total_fiber_length,
                                                        :actin_stat_path)";
        $q = $conn->prepare($addCellAnalytics);
        $q->bindParam(":cell_id", $cell_id);
        $q->bindParam(":nucleus_volume", $nucleus_volume);
        $q->bindParam(":total_fiber_num", $total_fiber_num);
        $q->bindParam(":total_fiber_volume", $total_fiber_volume);
        $q->bindParam(":total_fiber_length", $total_fiber_length);
        $q->bindParam(":actin_stat_path", $actin_stat_path);
        $q->execute();
    }

    public function getCellAnalytics($email) {
        $conn = $this->getConnection();
        return $conn->query("SELECT * FROM cells c
                                    JOIN analysed_cells a 
                                    ON c.cell_id = a.cell_id
                                    WHERE c.email = '$email'");
    }


} // end Dao