<?php

abstract class Attribute {
    private $name;
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getValue() {

    }

}

?>