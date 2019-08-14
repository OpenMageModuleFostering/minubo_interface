<?php
//External script - Load magento framework
require_once("../../../../..//Mage.php");
Mage::app('default');

$myOrder=Mage::getModel('sales/order');
$orders=Mage::getModel('sales/mysql4_order_collection');

//Optional filters you might want to use - more available operations in method _getConditionSql in Varien_Data_Collection_Db.
$orders->addFieldToFilter('total_paid',Array('gt'=>0)); //Amount paid larger than 0
$orders->addFieldToFilter('status',Array('eq'=>"processing"));  //Status is "processing"

$allIds=$orders->getAllIds();
foreach($allIds as $thisId) {
    $myOrder->load($thisId);
    //echo "<pre>";
    //print_r($myOrder);
    //echo "</pre>";

    //Some random fields
    echo "'" . $myOrder->getBillingAddress()->getLastname() . "',";
    echo "'" . $myOrder->getTotal_paid() . "',";
    echo "'" . $myOrder->getShippingAddress()->getTelephone() . "',";
    echo "'" . $myOrder->getPayment()->getCc_type() . "',";
    echo "'" . $myOrder->getStatus() . "',";
    echo "\r\n";
}
?>