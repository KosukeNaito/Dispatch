<?php

require_once 'Builder.php';

class HTMLTableBuilder extends Builder {

    protected $tableData = array();
    protected $tableCol = array();
    protected $headers = array();

    function __construct() {
    }

    public function add($input) {
        array_push($this->tableCol, $input);
        return true;
    }

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

    public function newLine() {
        array_push($this->tableData, $this->tableCol);
        $this->tableCol = array();
    }

    public function write() {
        $this->initText();
        $this->text .= '<table border="1">';
        $this->writeHeaders();
        $this->writeTableData();
        $this->text .= '</table>';
        print($this->text);
    }

    public function addHeader($input) {
        $this->headers[] = $input;
    }

    protected function writeTableData() {
        foreach ($this->tableData as $col) {
            $this->startRow();
            foreach ($col as $data) {
                $this->startData();
                $this->text .= $data;
                $this->endData();
            }
            $this->endRow();
        }
    }

    protected function startData() {
        $this->text .= '<td>';
    }

    protected function endData() {
        $this->text .= '</td>';
    }

    protected function writeHeaders() {
        $this->startRow();
        foreach ($this->headers as $header) {
            $this->text .= '<th>' . $header . '</th>';
        }
        $this->endRow();
    }

    protected function startRow() {
        $this->text .= '<tr>';
    }

    protected function endRow() {
        $this->text .= '</tr>';
    }


    protected function isEqualNextIndex($colIndex, $rowIndex) {
        return isEqualColSize($colIndex) && isEqualNextRowIndex($rowIndex);
    }

    protected function isOverColSize($colIndex) {
        return count($this->table) < $colIndex;
    }

    protected function isEqualColSize($colIndex) {
        return count($this->table) === $colIndex;
    }

    protected function isEqualNextRowIndex($rowIndex) {
        return $rowIndex === count($this->tableCol);
    }

    protected function isOverRowSize($colIndex, $rowIndex) {
        return count($this->table[$colIndex]) < $rowIndex;
    }

    protected function isEqualRowSize($colIndex, $rowIndex) {
        return count($this->table[$colIndex]) === $rowIndex;
    }

}


?>