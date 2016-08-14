<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace evecrestapi;

/**
 * Quantum Kittys Eve Crest API
 * @author Roy
 */
interface IEveCrestApi {
    
    public static function getInstance();
    
    /**
     * Retrieves the best selling price for the named item in the Jita region
     * @param String $name - Being the name of the item to get the price for
     * @return float The items best price in the Jita region
     */
    public function getJitaSell($name);
    
    /**
     * Retrieves the best buying price for the named item in the Jita region
     * @param String $name - Being the name of the item to get the price for
     * @return float The items best price in the Jita region
     */
    public function getJitaBuy($name);
    
    /**
     * Retrieves the best selling price for the named item in the Jita region
     * less the specified percentage
     * @param String $name - Being the name of the item to get the price for
     * @param float $perc - Number between 0 and 100 being the percentage to reduce
     * the retrieved price by. Default is 10%
     * @return float The items price less the specified percentage.
     */
    public function getJitaSellLessPerc($name, $perc = 10);
    
    /**
     * Retrieves the best buying price for the named item in the Jita region
     * less the specified percentage
     * @param String $name - Being the name of the item to get the price for
     * @param float $perc - Number between 0 and 100 being the percentage to reduce
     * the retrieved price by. Default is 10%
     * @return float The items price less the specified percentage.
     */
    public function getJitaBuyLessPerc($name, $perc = 10);
    
    /**
     * Tests to determine if the API recognises the name parameter as a valid
     * inventory item in the Eve online universe.
     * @param String $name - being the name of the item
     * @return Boolean TRUE if the item is recognised as an Eve inventory item,
     * FALSE otherwise
     */
    public function itemExists($name);

}
