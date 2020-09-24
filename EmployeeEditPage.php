<?php

require_once 'HTMLBuilder/EditableTableBuilder.php';
require_once 'DBConfig/DBConfig.php';
require_once 'DBConnector/DBConnector.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $addCount = $_POST['addCount'];
}

if (isset($_POST['add'])) {
    $addCount++; 
}

if (!isset($addCount)) {
    $addCount = 0;
}

$colCount    = 0;
$htmlTable = new EditableTableBuilder();
try {
    $dbc = new DBConnector();

    $fieldArray = $dbc->fetchField();
    //データベースのフィールド名をhtmlテーブルに追加
    $htmlTable->addHeader('削除');
    foreach ($fieldArray as $row) {
        $htmlTable->addHeader(h($row));
    }

    //hmtlテーブルに追加
    foreach ($dbc->fetchAllData() as $row) {
        foreach ($fieldArray as $field) {
            $htmlTable->add(h($row[$field]));
        }
        $htmlTable->newLine();
        $colCount++;
    }

    //+ボタンを押された回数　新規登録行を追加する
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        for ($i = 0; $i < $addCount; $i++) {
            $htmlTable->add('');
            $htmlTable->add('');
            $htmlTable->newLine();
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
$htmlTable->write();
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