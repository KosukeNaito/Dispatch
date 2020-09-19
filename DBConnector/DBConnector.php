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

    public function insertData($data) {
        if (count($data) !== $this->fetchFieldSize()) {
            return false;
        }
        $placeholder = generateInsertPlaceholder();
        $statementHandle = $dbh->prepare('INSERT INTO '.$tableName.' VALUES ('.$placeholder.')');
        //TODO:引数をプレースホルダーに入れる処理に変更
        foreach ($fieldArray as $field) {
            if (isset($_POST['input'.$c.$r]) && $_POST['input'.$c.'0'] !== '') {
                $statementHandle->bindValue(':'.$field, $_POST['input'.$c.$r]);
            }
            $r++;
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