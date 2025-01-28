<?php
class Equipe implements JsonSerializable{
    
    private $id;
    private $name;

    public function __construct($id, $name){
        $this->id = $id;
        $this->name = $name;
    }

    public function getName(){
        return $this->name;
    }
    
    public function getId(){
        return $this->id;
    }

    public function jsonSerialize():mixed {
        $vars = get_object_vars($this);

        return $vars;
    }
}