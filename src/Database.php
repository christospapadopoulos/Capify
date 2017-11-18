<?php

class Database {
    protected static $instance = null;
    protected $dbh;


    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self(Constants::DB_MAIN_USERNAME, Constants::DB_MAIN_PASSWORD, Constants::DB_MAIN_HOST, Constants::DB_MAIN_DBNAME);
        }
        return self::$instance;
    }

    private function __construct($username, $password, $host, $database) {
        try {
            $this->dbh = new PDO("mysql:host=$host;dbname=$database", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

        } catch (PDOException $e) {
            echo($e->getMessage());
        }
    }

    public function getDbh() {
        return $this->dbh; // returns the database handler to be used elsewhere
    }

    public function __destruct() {
        $this->dbh = null; // destroys the database handler when no longer needed
    }

    public function executeMysqlQuery($debug, $dblink, $sql, $params = array()) {
        $result = $dblink->prepare($sql);
        $result->execute($params);
        if ($result->errorCode() == "0") {
            //Executed succesfully
            return array(
                'status' => true,
                'result' => $result
            );
        } else {
            if ($debug) $errorMessage = "<b>Database error: </b>".var_export($result->errorInfo(), true)."<br /><b>Query: </b>$sql";
            else $errorMessage = "Database error.";
            return array(
                'status' => false,
                'error' => array(
                    'status' => "error",
                    'message' => $errorMessage
                )
            );
        }
    }
}
