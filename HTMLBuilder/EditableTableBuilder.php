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
            $tr->addTag($this->createTd($this->createCheckBox('checkbox'.$colCount)));
            $rowCount = 0;
            foreach ($col as $data) {
                $input = new Tag('input', '');
                $input->addAttribute('type', 'text');
                $input->addAttribute('value', $data);
                $input->addAttribute('name', 'input'.$colCount.$rowCount);
                $tr->addTag($this->createTd($input));
                $rowCount++;
            }
            $this->tableTag->addTag($tr);
            $colCount++;
        }
    }

    private function createCheckBox($name) {
        $checkbox = new Tag('input', '');
        $checkbox->addAttribute('type', 'checkbox');
        $checkbox->addAttribute('name', $name);
        return $checkbox;
    }

    private function createTd($tag) {
        $td = new Tag('td', '');
        $td->addTag($tag);
        return $td;
    }

}

?>