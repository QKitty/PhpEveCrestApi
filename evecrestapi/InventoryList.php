<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace evecrestapi;

require_once 'InventoryListEntry.php';

/**
 * A collection of InventoryListEntry items
 *
 * @author Roy
 */
class InventoryList {

    private $theList;

    private function __construct() {
        $this->theList = array();
    }

    public static function InventoryList() {
        $instance = new InventoryList();
        return $instance;
    }

    public static function InventoryListFromData($data) {
        $instance = new InventoryList();
        //Process a copy pasted entry
        $rows = preg_split("/\t\d+\.\d{2} m3/", $data, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($rows as $line) {
            $listEntry = InventoryListEntry::InventoryListEntry($line);
            $instance->add($listEntry);
        }
        return $instance;
    }

    public function add($listEntry) {
        if ($listEntry instanceof InventoryListEntry) {
            array_push($this->theList, $listEntry);
        } else {
            throw new Exception("Can only add InventoryListEntry items to an InventoryList");
        }
    }

    public function getTotalJitaSellingPrice() {
        $result = 0.0;
        foreach ($this->theList as $item) {
            $result += $item->getTotalJitaSellPrice();
        }
        return $result;
    }

    public function getTotalJitaBuyingPrice() {
        $result = 0.0;
        foreach ($this->theList as $item) {
            $result += $item->getTotalJitaBuyPrice();
        }
        return $result;
    }

    public function getTotalJitaSellingPriceLessPerc($perc = 10.0) {
        $result = 0.0;
        foreach ($this->theList as $item) {
            $result += $item->getTotalJitaSellLessPerc($perc);
        }
        return $result;
    }

    public function getTotalJitaBuyingPriceLessPerc($perc = 10.0) {
        $result = 0.0;
        foreach ($this->theList as $item) {
            $result += $item->getTotalJitaBuyLessPerc($perc);
        }
        return $result;
    }

    public function toArray() {
        $result = array();
        foreach ($this->theList as $item) {
            array_push($result, $item);
        }
        return $result;
    }

    public function toString() {
        $result = "";
        foreach ($this->theList as $item) {
            $currString = $item->toString();
            $currString = $currString . "<br>";
            $result = $result . $currString;
        }
        return $result;
    }

    public function toStringSellLessPerc($perc = 10.0) {
        $result = "Item,Qty,Price,Amount<br>";
        foreach ($this->theList as $item) {
            $currString = $item->toStringSellLessPerc($perc);
            $currString = $currString . "<br>";
            $result = $result . $currString;
        }
        $result = $result . "Total,,," . $this->getTotalJitaSellingPriceLessPerc($perc) . "<br>";
        return $result;
    }

}
