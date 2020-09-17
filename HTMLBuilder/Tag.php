<?php

/**
 * 
 * HTMLのタグを表現するクラス
 * 
 * PHPにおけるHTMLの記述時に使用
 * コンストラクタでタグの要素名（$tagName）とタグで囲むコンテンツ（$content）を指定
 * writeメソッドで指定されたタグで囲まれたコンテンツを出力する
 * 属性、子要素も追加可
 * 
 * @access public
 * @author 内藤昂佑
 */
class Tag implements TagInterface {
    private $tagName;                   //タグの要素名
    private $text       = '';           //writeで出力される文
    private $tags       = array();      //タグの子要素
    private $attributes = array();      //タグの属性
    private $content    = '';           //タグに囲まれたコンテンツ

    /**
     * タグの要素名（$tagName）とタグで囲むコンテンツをセット
     */
    public function __construct($tagName, $content) {
        $this->tagName = h($tagName);
        $this->content = h($content);
    }

    /**
     * タグで囲むコンテンツをセット
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * 子要素となるタグを追加する
     */
    public function addTag($tag) {
        if ($tag instanceof Tag) {
            $this->tags[] = $tag;
        }
    }

    /**
     * タグの属性を追加する
     * <input type='text'>
     * 上を表現したいとき$nameに'type', $valueに'text'を入れる（inputは要素名$tagName） 
     */
    public function addAttribute($name, $value) {
        $this->attributes[$this->h($name)] = $this->h($value);
    }

    /**
     * このタグクラスから生成されたHTML文字列を返す
     */
    public function getTagText() {
        $this->writeTagStart();
        $this->writeContent();
        $this->writeTagEnd();
        return $this->text;
    }

    /**
     * このタグクラスから生成されたHTML文字列を表示する
     */
    public function write() {
        print $this->getTagText();
    }

    /**
     * 開始タグを記述する
     */
    protected function writeTagStart() {
        $this->text .= '<' . $this->tagName;
        $this->writeAttributes();
        $this->text .= '>';
    }

    /**
     * 閉じタグを記述する
     */
    protected function writeTagEnd() {
        $this->text .= '</' . $this->tagName;
        $this->text .= '>';
    }

    /**
     * 開始タグと閉じタグの間の要素を記述する
     * このとき子要素のgetTagText()を呼び、子要素の文字列も記述する
     */
    protected function writeContent() {
        $this->text .= $this->content;
        foreach ($this->tags as $tag) {
            $this->text .= $tag->getTagText();
        }
    }

    /**
     * 追加された属性をすべて書き出す
     * 開始タグを記述する際に呼ばれる（writeTagStart()）
     */
    protected function writeAttributes() {
        foreach ($this->attributes as $key => $value) {
            $this->text .= ' ' . $key . '=\'' . $value . '\'';
        }
    }

    protected function h($str) {
        return htmlspecialchars($str);
    }

}


interface TagInterface {
    public function write();
}



?>