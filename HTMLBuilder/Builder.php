<?php
/**
 * 抽象クラス
 * 
 * PHPでHTMLを書かせるために作ったクラス
 * 抽象的に考えた結果 入力されたものをHTML文としてwriteするものと考え実装
 * あまり良くない出来となった
 * 
 */
abstract class Builder implements BuilderInterface {

    private $text = '';

    public function write() {
        print($text);
    }

    public function add($input) {
        $this->text .= $input;
    }

    protected function initText() {
        $this->text = '';
    }

}

interface BuilderInterface {
    public function write();
    public function add($input);
}

?>