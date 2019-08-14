<?php
/**
 * Magento Minubo Interface Export Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Minubo
 * @package    Minubo_Interface
 * @copyright  Copyright (c) 2013 Minubo (http://www.minubo.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Sven Rothe <srothe@minubo.com>
 * */
class Minubo_Interface_Model_Read_Orders extends Minubo_Interface_Model_Read_Common
{
    /**
     * Concrete implementation of abstract method to export given orders to csv file in var/export.
     *
     * @param $orders List of orders of type Mage_Sales_Model_Order or order ids to export.
     * @return String The name of the written csv file in var/export
     */
    public function read($lastChangeDate, $lastOrderID, $maxOrderID, $limit)
    {

			// Bestellungen laden
			$orders = Mage::getModel('sales/order')->getCollection();

			// CSV-Header
			//echo '"Name, Vorname","Straße","Kto","BLZ","Kontoinhaber"' . "\n";

			// Bestellungen durchgehen
			/*
			foreach($sales as $order) {
			    // Payment-Methode überprüfen
			    if($order->getPayment()->getMethod() == 'debit') {
			        // Payment-Instance laden
			        $_pi = $order->getPayment()->getMethodInstance();

			        // Adresse laden
			        $_ba = $order->getBillingAddress();

			        // Daten ausgeben
			        echo '"' . $_ba->getCompany() . '","' . implode(', ', $_ba->getStreet()) . '","' . $_pi->getAccountNumber() . '","' . $_pi->getAccountBLZ() . '","' . $_pi->getAccountName() . '"' . "\n";
			    }
			}
			*/

			$i=0;
			foreach($orders as $key=>$order){
        //product
        if($order->getUpdatedAt()<=$lastChangeDate || $order->getIncrementId()<=$lastOrderID || $order->getIncrementId()>$maxOrderID) {
        	// echo $key.' '.$order->getIncrementId().' '.$order->getUpdatedAt(),'<br>';
        	$orders->removeItemByKey($key);
        } else {
        	$i++;
        	if($i>$limit) $orders->removeItemByKey($key);
        }
    	}

      return $orders;
    }

}
?>