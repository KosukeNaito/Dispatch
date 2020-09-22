<?php
require_once 'DBConnector/DBConnector.php';

$dbc = new DBConnector();
if ($dbc->addNewAttribute($_POST['attribute'])) {
    echo '属性追加成功';
} else {
    echo '属性追加失敗';
}

?>

<!DOCTYPE html>
<html>

<head>
</head>

<body>
<title>属性編集</title>

<a href='AttributeEditPage.php'>戻る</a>
<a href='index.php'>トップへ</a>
</body>

</html>