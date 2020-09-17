<?php

require_once('DBConfig/DBConfig.php');

$dsn        = DBConfig::$DATA_SOURCE_NAME;
$user       = DBConfig::$USER_NAME;
$password   = DBConfig::$PASSWORD;
$tableName  = DBConfig::$TABLE_NAME;

if (isset($_POST['regist'])) {
    if (isset($_POST['rowCount']) && isset($_POST['colCount'])) {
        $dbh = new PDO($dsn, $user, $password);

        $getClmQuery = 'SHOW COLUMNS FROM ' . $tableName;
        $values = '';
        $fieldArray = array();
        foreach ($dbh->query($getClmQuery) as $row) {
            $fieldArray[] = $row['Field'];
            $values .= ':'. $row['Field'] . ',';
        }
        $values = rtrim($values, ',');

        $selectQuery = 'SELECT * FROM '.$tableName;
        $employeeNumArray = array();
        foreach ($dbh->query($selectQuery) as $row) {
            $employeeNumArray[] = $row['number'];
        }

        $statementHandle = '';
        $updateFlag = false;
        for ($c = 0; $c < $_POST['colCount']; $c++) {
            if ($c < count($employeeNumArray)) {
                $statementHandle = $dbh->prepare('UPDATE '.$tableName.' SET ');
            } else {
                $statementHandle = $dbh->prepare('INSERT INTO '.$tableName.' VALUES ('.$values.')');
            }
        
            $r = 0;
            foreach ($fieldArray as $field) {
                if (isset($_POST['input'.$c.$r]) && $_POST['input'.$c.'0'] !== '') {
                    $statementHandle->bindValue(':'.$field, $_POST['input'.$c.$r]);
                }
                $r++;
            }
            $statementHandle->execute();
            if ($statementHandle->rowCount() !== 0) {
                echo ($c + 1).'行目が更新されました。<br>';
                $updateFlag = true;
            }
        }
        if (!$updateFlag) {
            echo '更新が失敗しました。<br>';
        }
    }
} else {
    echo '強制ブラウジングエラー';
    die();
}

?>

<html>
<head>
</head>

<body>
<title>従業員編集</title>
<a href='EmployeeEditPage.php'>戻る</a>
<a href='index.php'>トップへ</a>
</body>

</html>