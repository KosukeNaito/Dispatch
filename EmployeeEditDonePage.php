<?php

require_once 'DBConfig/DBConfig.php';
require_once 'DBConnector/DBConnector.php';

$dsn        = DBConfig::DATA_SOURCE_NAME;
$user       = DBConfig::USER_NAME;
$password   = DBConfig::PASSWORD;
$tableName  = DBConfig::TABLE_NAME;

if (isset($_POST['regist'])) {
    if (isset($_POST['rowCount']) && isset($_POST['colCount'])) {
        $dbh = new PDO($dsn, $user, $password);
        $dbc = new DBConnector();

        $updateFlag = false;
        for ($c = 0; $c < $_POST['colCount']; $c++) {

            $input = array();

            for ($r = 0; $r < $_POST['rowCount']; $r++) {
                $input[] = $_POST['input'.$c.$r];
            }

            if ($c < $dbc->fetchAllDataSize()) {
                //$statementHandle = $dbh->prepare('UPDATE '.$tableName.' SET ');
            } else {
                $dbc->insertData($input);                
            }
        
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