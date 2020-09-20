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
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
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

    public function fetchData($field, $data) {
        $dbh = $this->connect();
        $selectQuery = 'SELECT * FROM ' .$this->tableName. ' WHERE '.$field.'="'.$data.'"';
        return $dbh->query($selectQuery)->fetchAll();
    }

    public function fetchAllDataSize() {
        return count($this->fetchAllData());
    }

    public function insertData($insertValues) {
        if (!is_array($insertValues)) {
            return false;
        }

        if (count($insertValues) !== $this->fetchFieldSize()) {
            return false;
        }

        if ($insertValues[0] === '') {
            return false;
        }

        $placeholder = $this->generateInsertPlaceholder();
        $dbh = $this->connect();
        $field = $this->fetchField();
        $statementHandle = $dbh->prepare('INSERT INTO '.$this->tableName.' VALUES ('.$placeholder.')');
        for ($i = 0; $i < $this->fetchFieldSize(); $i++) {
            $statementHandle->bindValue(':'.$field[$i], $insertValues[$i]);
        }
        return $statementHandle->execute();
    }

    public function updateData($updateValue, $whereValue) {
        $dbh = $this->connect();
        $field = $this->fetchField();
        $placeholder = $this->generateUpdatePlaceholder();
        $statementHandle = $dbh->prepare('UPDATE '.$this->tableName.' SET '.$placeholder.' WHERE number="'.$whereValue.'"');
        for ($i = 0; $i < $this->fetchFieldSize(); $i++) {
            $statementHandle->bindValue(':'.$field[$i], $updateValue[$i]);
        }
        return $statementHandle->execute();
    }

    private function generateUpdatePlaceholder() {
        $fieldArray = $this->fetchField();
        $values = '';
        foreach ($fieldArray as $field) {
            $values .= $field . ' = :' . $field . ','; 
        }
        return rtrim($values, ',');
    }

    private function generateInsertPlaceholder() {
        $fieldArray = $this->fetchField();
        $values = '';
        foreach ($fieldArray as $field) {
            $values .= ':'. $field . ',';
        }
        return rtrim($values, ',');
    }

 
}


?>