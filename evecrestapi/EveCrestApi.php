<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace evecrestapi;

require_once 'InventoryItem.php';

/**
 * Description of EveCrestApi
 *
 * @author Roy
 */
class EveCrestApi implements IEveCrestApi {

    private $itemData;
    private static $myself = null;
    
    public static function getInstance() {
        if(null == EveCrestApi::$myself){
            EveCrestApi::$myself = new EveCrestApi();
        }
        return EveCrestApi::$myself;
    }

    private function __construct() {
        $this->loadItemData();
    }

    public function getJitaBuy($name) {
        $result = 0.0;
        $item = $this->getInventoryItemFromName($name);
        if (null != $item) {
            $dataUrl = "https://crest-tq.eveonline.com/market/10000002/orders/buy/?type=https://crest-tq.eveonline.com/inventory/types/" . $item->getId() . "/";
            $json_data = $this->makeCrestAPICall($dataUrl);
            $result = $this->parsePriceJsonData($json_data, FALSE);
        }
        return $result;
    }

    public function getJitaSell($name) {
        $result = 0.0;
        $item = $this->getInventoryItemFromName($name);
        if (null != $item) {
            $dataUrl = "https://crest-tq.eveonline.com/market/10000002/orders/sell/?type=https://crest-tq.eveonline.com/inventory/types/" . $item->getId() . "/";
            $json_data = $this->makeCrestAPICall($dataUrl);
            $result = $this->parsePriceJsonData($json_data);
        }
        return $result;
    }
    
    public function getJitaBuyLessPerc($name, $perc = 10) {
        if($perc > 100){
            $perc = 100;
        }
        if($perc < 0){
            $perc = 0;
        }
        $percDec = $perc / 100;
        $value = $this->getJitaBuy($name);
        return $value - ($value * $percDec);
    }

    public function getJitaSellLessPerc($name, $perc = 10) {
        if($perc > 100){
            $perc = 100;
        }
        if($perc < 0){
            $perc = 0;
        }
        $percDec = $perc / 100;
        $value = $this->getJitaSell($name);
        return $value - ($value * $percDec);
    }
    
    public function itemExists($name) {
        return array_key_exists(trim($name), $this->itemData);
    }
    
    private function getInventoryItemFromName($name) {
        $result = null;
        $name = trim($name);
        if ($this->itemExists($name)) {
            $result = $this->itemData[$name];
        }
        return $result;
    }

    private function makeCrestAPICall($endpoint) {
        $response = file_get_contents($endpoint);
        return $response;
    }

    private function parsePriceJsonData($json, $sell = TRUE) {
        $result = null;
        $data = json_decode($json, true);
        $ordersList = $data["items"];
        foreach ($ordersList as $key => $value) {
            if (null == $result) {
                $result = $value["price"];
            } else {
                if ($sell) {
                    if ($value["price"] < $result) {
                        $result = $value["price"];
                    }
                } else {
                    if ($value["price"] > $result) {
                        $result = $value["price"];
                    }
                }
            }
        }
        return $result;
    }

    private function loadItemData() {
        $handle = fopen(".\\evecrestapi\\typeid.txt", "r");
        if ($handle) {
            $this->itemData = array();
            while (($line = fgets($handle)) !== false) {
                $pieces = explode(",", $line);
                $anId = $pieces[0];
                $aName = $pieces[1];
                $nextItem = InventoryItem::InventoryItem($anId, $aName);
                $this->itemData[$nextItem->getName()] = $nextItem;
            }
            fclose($handle);
        } else {
            // error opening the file.
            throw new \Exception('Error reading item ID file');
        }
    }

    

}
