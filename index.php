<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        include_once './evecrestapi/EveCrestApiHeader.php';
        //$crest = new evecrestapi\EveCrestApi();
        $crest = \evecrestapi\EveCrestApi::getInstance();
        $itemName = "Arkonor";
        $itemSell = $crest->getJitaSell($itemName);
        $itemBuy = $crest->getJitaBuy($itemName);
        $itemSellLessPerc = $crest->getJitaSellLessPerc($itemName);
        $itemBuyLessPerc = $crest->getJitaBuyLessPerc($itemName);
        echo $itemName . " Sells for: " . $itemSell . " @ - 10% = " . $itemSellLessPerc;
        echo '<br>';
        echo $itemName . " Buys at: " . $itemBuy . " @ - 10% = " . $itemBuyLessPerc;
        echo '<br>';
        $copied = "Alloyed Tritanium Bar	582	Salvaged Materials			5.82 m3
Armor Plates	141	Salvaged Materials			1.41 m3";
        $listOfItems = \evecrestapi\InventoryList::InventoryListFromData($copied);
        $total = $listOfItems->getTotalJitaSellingPriceLessPerc();
        echo "Total value = " . $total;
        echo '<br>';
        echo $listOfItems->toStringSellLessPerc();
        ?>
    </body>
</html>
