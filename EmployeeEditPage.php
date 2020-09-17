<?php

require_once 'HTMLBuilder/EditableTableBuilder.php';
require_once 'DBConfig/DBConfig.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $addCount = $_POST['addCount'];
}

if (isset($_POST['add'])) {
    $addCount++; 
}

if (!isset($addCount)) {
    $addCount = 0;
}

$dsn        = DBConfig::DATA_SOURCE_NAME;
$user       = DBConfig::USER_NAME;
$password   = DBConfig::PASSWORD;
$tableName  = DBConfig::TABLE_NAME;

$colCount    = 0;
$htmlBuilder = new EditableTableBuilder();
try {
    $dbh = new PDO($dsn, $user, $password);

    //データベースのフィールド名を取得
    $getClmQuery = 'SHOW COLUMNS FROM ' . $tableName;
    $fieldArray = $dbh->query($getClmQuery)->fetchAll(PDO::FETCH_COLUMN, 0);

    //データベースのフィールド名をテーブルに追加
    foreach ($fieldArray as $row) {
        $htmlBuilder->addHeader(h($row));
    }

    //データをテーブルに追加
    $selectQuery = 'SELECT * FROM ' . $tableName;
    foreach ($dbh->query($selectQuery) as $row) {
        foreach ($fieldArray as $field) {
            $htmlBuilder->add(h($row[$field]));
        }
        $htmlBuilder->newLine();
        $colCount++;
    }

    //+ボタンを押された回数　新規登録行を追加する
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        for ($i = 0; $i < $addCount; $i++) {
            $htmlBuilder->add('');
            $htmlBuilder->add('');
            $htmlBuilder->newLine();
            $colCount++;
        }
    }
} catch (PDOException $e){
    print($e->getMessage());
    die();
}


function h($str) {
    return htmlspecialchars($str);
}

?>

<!DOCTYPE html>
<html>

<head>
</head>

<body>
<title>従業員編集</title>
<form action='EmployeeEditDonePage.php' method='POST' style="display:inline">
<?php
$htmlBuilder->write();
?>
<input type='submit' name='regist' value='登録'>
<input type='hidden' name='rowCount' value='<?php echo count($fieldArray) ?>'>
<input type='hidden' name='colCount' value='<?php echo $colCount ?>'>
</form>
<form action='EmployeeEditPage.php' method='POST' style="display:inline">
<input type='submit' name='add' value='+'> <br>
<input type='hidden' name='addCount' value='<?php echo $addCount ?>'>
</form>
<a href='index.php'>トップへ</a>
</body>

</html>