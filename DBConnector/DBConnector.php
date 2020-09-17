<?php

require_once 'DBConfig/DBConfig.php';

class DBConnector {

    private $dsn        = DBConfig::DATA_SOURCE_NAME;
    private $user       = DBConfig::USER_NAME;
    private $password   = DBConfig::PASSWORD;
    private $tableName  = DBConfig::TABLE_NAME;

    function __construct() {

    }

    private function connect() {
        try {
            $dbh = new PDO($this->dsn, $this->user, $this->password);
        } catch (PDOException $e) {
            print($e->getMessage());
            die();
        }
        return $dbh;
    }

    public function fetchField() {
        $dbh = $this->connect();
        $showClmQuery = 'SHOW COLUMNS FROM ' . $this->tableName;        
        return $dbh->query($showClmQuery)->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function fetchAllData() {
        $dbh = $this->connect();
        $allSelectQuery = 'SELECT * FROM ' . $this->tableName;
        return $dbh->query($allSelectQuery)->fetchAll();
    }

}


?>