<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace evecrestapi;

require_once 'EveCrestApi.php';

/**
 * This class takes a copy / pasted list entry from an Eve Online inventory
 * screen in list mode and parses out the name and qty values
 *
 * @author Roy
 */
class InventoryListEntry {

    private $name;
    private $qty;
    private $jitaSell;
    private $jitaBuy;

    private function __construct() {
        $this->name = "UNKNOWN";
        $this->qty = 0;
        $this->jitaSell = 0.0;
        $this->jitaBuy = 0.0;
    }

    public static function InventoryListEntry($line) {
        $instance = new self();
        $data = preg_split("/\t/", $line);
        $instance->setName($data[0]);
        $instance->setQty($data[1]);
        return $instance;
    }

    public function getName() {
        return $this->name;
    }

    public function getQty() {
        return intval($this->qty);
    }

    public function getTotalJitaSellPrice() {
        $this->loadPrices();
        return $this->jitaSell * $this->qty;
    }

    public function getTotalJitaBuyPrice() {
        $this->loadPrices();
        return $this->jitaBuy * $this->qty;
    }
    
    public function getJitaSellingPrice() {
        $this->loadPrices();
        return $this->jitaSell;
    }

    public function getJitaBuyingPrice() {
        $this->loadPrices();
        return $this->jitaBuy;
    }

    public function getTotalJitaSellLessPerc($perc = 10.0) {
        if ($perc > 100) {
            $perc = floatval(100.0);
        }
        if ($perc < floatval(0.0)) {
            $perc = floatval(0.0);
        }
        $perc /= 100;
        $multiple = floatval($perc);
        $this->loadPrices();
        $result = ($this->jitaSell - ($this->jitaSell * $multiple)) * $this->qty;
        return $result;
    }
    
    public function getJitaSellingLessPerc($perc = 10.0) {
        if ($perc > 100) {
            $perc = floatval(100.0);
        }
        if ($perc < floatval(0.0)) {
            $perc = floatval(0.0);
        }
        $perc /= 100;
        $multiple = floatval($perc);
        $this->loadPrices();
        $result = ($this->jitaSell - ($this->jitaSell * $multiple));
        return $result;
    }

    public function getTotalJitaBuyLessPerc($perc = 10.0) {
        if ($perc > 100) {
            $perc = floatval(100.0);
        }
        if ($perc < floatval(0.0)) {
            $perc = floatval(0.0);
        }
        $perc /= 100;
        $multiple = floatval($perc);
        $this->loadPrices();
        $result = ($this->jitaBuy - ($this->jitaBuy * $multiple)) * $this->qty;
        return $result;
    }
    
    public function getJitaBuyingLessPerc($perc = 10.0) {
        if ($perc > 100) {
            $perc = floatval(100.0);
        }
        if ($perc < floatval(0.0)) {
            $perc = floatval(0.0);
        }
        $perc /= 100;
        $multiple = floatval($perc);
        $this->loadPrices();
        $result = ($this->jitaBuy - ($this->jitaBuy * $multiple));
        return $result;
    }

    public function toString() {
        $result = $this->name . ", " . $this->qty;
        return $result;
    }
    
    public function toStringSellLessPerc($perc = 10.0) {
        $result = $this->name . "," . $this->qty . "," . $this->getJitaSellingLessPerc($perc) . "," . $this->getTotalJitaSellLessPerc($perc);
        return $result;
    }

    private function setName($aName) {
        $this->name = trim($aName);
    }

    private function setQty($aQty) {
        $this->qty = intval($aQty);
        if ($this->qty < 1) {
            $this->qty = 1;
        }
    }

    private function loadPrices() {
        if ($this->jitaSell == 0 || $this->jitaBuy == 0) {
            $crest = EveCrestApi::getInstance();
            if ($crest->itemExists($this->name)) {
                $this->jitaSell = $crest->getJitaSell($this->name);
                $this->jitaBuy = $crest->getJitaBuy($this->name);
            } else {
                $this->jitaSell = 0.0;
                $this->jitaBuy = 0.0;
            }
        }
    }

}
