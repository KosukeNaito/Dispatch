<?php

use PHPUnit\Framework\TestCase;

class TagTest extends TestCase {

    public function testConstruct() {
        require 'HTMLBuilder/Tag.php';

        $tag = new Tag('tagName', 'testContent');
        $this->assertEquals('<tagName>testContent</tagName>', $tag->getTagText());

        $tag = new Tag('emptyTag', '');
        $this->assertEquals('<emptyTag></emptyTag>', $tag->getTagText());
    }

    public function testSetContent() {

        $tag = new Tag('tagName', 'testContent');
        $tag->setContent('overrideContent');
        $this->assertEquals('<tagName>overrideContent</tagName>', $tag->getTagText());

    }

    public function testWriteAttributes() {

        $tag = new Tag('attTest', '');
        $tag->addAttribute('class', 'testClass');
        $this->assertEquals('<attTest class=\'testClass\'></attTest>', $tag->getTagText());


    }

}


?>