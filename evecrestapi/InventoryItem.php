<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace evecrestapi;

/**
 * This class represents an item that might be bought or sold
 *
 * @author Roy
 */
class InventoryItem {
    private $id;
    private $name;
    
    public function __construct() {
        $this->id = 0;
        $this->name = "#System";
    }
    
    public static function InventoryItem($anId, $aName){
        $instance = new self();
        $instance->setId($anId);
        $instance->setName($aName);
        return $instance;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getName(){
        return $this->name;
    }
    
    private function setId($anId){
        $this->id = trim($anId);
    }
    
    private function setName($aName){
        $this->name = trim($aName);
    }
}
