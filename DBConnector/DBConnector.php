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

    public function fetchFieldSize() {
        return count($this->fetchField());
    }

    public function fetchAllData() {
        $dbh = $this->connect();
        $allSelectQuery = 'SELECT * FROM ' . $this->tableName;
        return $dbh->query($allSelectQuery)->fetchAll();
    }

    public function fetchAllDataSize() {
        return count($this->fetchAllData());
    }

    public function insertData($data) {
        if (!is_array($data)) {
            return false;
        }

        if (count($data) !== $this->fetchFieldSize()) {
            return false;
        }

        if ($data[0] === '') {
            return false;
        }
        $placeholder = $this->generateInsertPlaceholder();
        $dbh = $this->connect();
        $field = $this->fetchField();
        $statementHandle = $dbh->prepare('INSERT INTO '.$this->tableName.' VALUES ('.$placeholder.')');
        for ($i = 0; $i < $this->fetchFieldSize(); $i++) {
            $statementHandle->bindValue(':'.$field[$i], $data[$i]);
        }
        return $statementHandle->execute();
    }

    private function generateInsertPlaceholder() {
        $fieldArray = $this->fetchField();
        foreach ($fieldArray as $field) {
            $values .= ':'. $field . ',';
        }
        return rtrim($values, ',');
    }

 
}


?>