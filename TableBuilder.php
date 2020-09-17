<?php

require_once 'Builder.php';
require_once 'Tag.php';

/**
 * htmlのtableを記述するクラス
 * addで要素を追加 newLineでテーブルを改行
 * writeで文字列を出力する
 * 
 * @author 内藤昂佑
 */
class TableBuilder extends Builder {

    protected $tableTag;                //要素名tableを持つTagクラス
    protected $tableData = array();     //テーブルデータを表す二次元配列
    protected $tableCol = array();      //$tableDataの一行となる配列
    protected $headers = array();       //テーブルヘッダーを表す配列

    /**
     * コンストラクタ
     * 
     * $tableTagの要素名をtableとする
     * また$tableTagの属性を設定するためsetTableAttributesを呼ぶ
     */
    function __construct() {
        $this->tableTag = new Tag('table', '');
        $this->setTableAttributes();
    }

    /**
     * $inputをテーブルデータに追加する
     */
    public function add($input) {
        array_push($this->tableCol, $input);
        return true;
    }

    /**
     * テーブルデータの$colIndex行$rowIndex列の値を修正する
     * 現在のテーブルデータの列の末尾を指定された場合addメソッドを呼び追加する
     */
    public function fix($input, $colIndex, $rowIndex) {
        if (isOverColSize($colIndex)) {
            return false;            
        }

        if (isOverRowSize($colIndex, $rowIndex)) {
            return false;
        }

        if (isEqualRowSize($colIndex, $rowIndex)) {
            $this->table[$colIndex][] = $input;
            return true;
        }

        if (isEqualNextIndex($colIndex, $rowIndex)) {
            return add($input);
        }

        $this->tableData[$colIndex][$rowIndex] = $input;
        return true;
    }

    /**
     * 次の行へ改行する
     */
    public function newLine() {
        array_push($this->tableData, $this->tableCol);
        $this->tableCol = array();
    }

    /**
     * 現在のテーブルデータをもとにtable文（HTML)を作成し表示する
     */
    public function write() {
        $this->initText();
        $this->makeHeaders();
        $this->makeTableData();
        $this->tableTag->write();
    }

    /**
     * テーブルヘッダーに値を追加する
     */
    public function addHeader($input) {
        $this->headers[] = $input;
    }

    /**
     * テーブルデータをHTML文に変換する
     */
    protected function makeTableData() {
        foreach ($this->tableData as $col) {
            $tr = new Tag('tr', '');
            foreach ($col as $data) {
                $td = new Tag('td', $data);
                $tr->addTag($td);
            }
            $this->tableTag->addTag($tr);
        }
    }

    /**
     * テーブルの属性を設定する
     */
    protected function setTableAttributes() {
        $this->tableTag->addAttribute('border', '1');
        $this->tableTag->addAttribute('style', 'background-color:EDF7FF;');
    }

    /**
     * ヘッダーをHTML文に変換する
     */
    protected function makeHeaders() {
        $tr = new Tag('tr' ,'');
        foreach ($this->headers as $header) {
            $th = new Tag('th', $header);
            $tr->addTag($th);
        }
        $this->tableTag->addTag($tr);
    }

    /**
     * 次にaddメソッドで追加されるところと同じかどうか
     */
    protected function isEqualNextIndex($colIndex, $rowIndex) {
        return isEqualColSize($colIndex) && isEqualNextRowIndex($rowIndex);
    }

    /**
     * 入力された行数が現在のテーブルのサイズを超えていないか
     */
    protected function isOverColSize($colIndex) {
        return count($this->table) < $colIndex;
    }

    /**
     * 入力された行数が現在のテーブルサイズと一致するか
     * またサイズと一致することは行の次の要素を指定していることと同義
     */
    protected function isEqualColSize($colIndex) {
        return count($this->table) === $colIndex;
    }

    /**
     * 入力された列数が現在の列のテーブルサイズと一致するか
     * またサイズと一致することは列の次の要素を指定していることと同義
     */
    protected function isEqualNextRowIndex($rowIndex) {
        return $rowIndex === count($this->tableCol);
    }

    /**
     * 入力された列数が現在のテーブルサイズを超えていないか
     */
    protected function isOverRowSize($colIndex, $rowIndex) {
        return count($this->table[$colIndex]) < $rowIndex;
    }

    /**
     * 入力された列数が指定された行の列サイズと一致するか
     */
    protected function isEqualRowSize($colIndex, $rowIndex) {
        return count($this->table[$colIndex]) === $rowIndex;
    }

}


?>