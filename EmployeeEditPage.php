<?php

require_once 'EditableTableBuilder.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $addCount = $_POST['addCount'];
}

if (isset($_POST['add'])) {
    $addCount++; 
}

if (!isset($addCount)) {
    $addCount = 0;
}

$dsn = 'mysql:dbname=dispatchdb;host=localhost';
$user = 'fmboy';
$password = '1gEZCoHbzT1CrWi8';
$tableName = 'employee';

$colCount = 0;
$rowCount = 0;
$htmlBuilder = new EditableTableBuilder();
try {
    $dbh = new PDO($dsn, $user, $password);

    $getClmQuery = 'SHOW COLUMNS FROM ' . $tableName;
    $fieldArray = array();
    foreach ($dbh->query($getClmQuery) as $row) {
        $htmlBuilder->addHeader(h($row['Field']));
        $fieldArray[] = $row['Field'];
        $rowCount++;
    }

    $selectQuery = 'SELECT * FROM ' . $tableName;
    foreach ($dbh->query($selectQuery) as $row) {
        foreach ($fieldArray as $field) {
            $htmlBuilder->add(h($row[$field]));
        }
        $htmlBuilder->newLine();
        $colCount++;
    }

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
<input type='hidden' name='rowCount' value='<?php echo $rowCount ?>'>
<input type='hidden' name='colCount' value='<?php echo $colCount ?>'>
</form>
<form action='EmployeeEditPage.php' method='POST' style="display:inline">
<input type='submit' name='add' value='+'> <br>
<input type='hidden' name='addCount' value='<?php echo $addCount ?>'>
</form>
<a href='index.php'>トップへ</a>
</body>

</html>