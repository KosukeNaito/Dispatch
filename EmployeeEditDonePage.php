<?php

require_once 'DBConfig/DBConfig.php';
require_once 'DBConnector/DBConnector.php';

if (isset($_POST['regist'])) {
    if (isset($_POST['rowCount']) && isset($_POST['colCount'])) {
        $dbc = new DBConnector();

        $allData = $dbc->fetchAllData();
        $updateFlags = array();
        for ($c = 0; $c < $_POST['colCount']; $c++) {

            $input = array();
            for ($r = 0; $r < $_POST['rowCount']; $r++) {
                $input[] = $_POST['input'.$c.$r];
            }

            if ($c < count($allData)) {
                $updateFlags[] = $dbc->updateData($input, $allData[$c]['number']);
            } else {
                $updateFlags[] = $dbc->insertData($input);             
            }
        }
        echo '処理が終了しました。<br>';
        for ($c = 0; $c < count($updateFlags); $c++) {
            if (!$updateFlags[$c]) { 
                print ($c+1)."の処理が失敗しました。<br>";
            }
        }

    }
} else if (isset($_POST['delete'])) {
    if (isset($_POST['colCount'])) {
        for ($c = 0; $c < $_POST['colCount']; $c++) {
            if ($_POST['checkbox'.$c]) {
                echo $c;
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