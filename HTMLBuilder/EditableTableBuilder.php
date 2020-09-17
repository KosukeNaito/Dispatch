<?php

require_once 'HTMLBuilder/TableBuilder.php';

/**
 * TableBuilderクラスのテーブルデータ各要素を編集可能にするもの
 */
class EditableTableBuilder extends TableBuilder {
    public function __construct() {
        parent::__construct();
    }

    protected function makeTableData() {
        $colCount = 0;
        foreach ($this->tableData as $col) {
            $tr = new Tag('tr', '');
            $rowCount = 0;
            foreach ($col as $data) {
                $input = new Tag('input', '');
                $input->addAttribute('type', 'text');
                $input->addAttribute('value', $data);
                $input->addAttribute('name', 'input'.$colCount.$rowCount);
                $td = new Tag('td', '');
                $td->addTag($input);
                $tr->addTag($td);
                $rowCount++;
            }
            $this->tableTag->addTag($tr);
            $colCount++;
        }
    }
}

?>