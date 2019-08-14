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

class Minubo_Interface_Model_Read_Collections extends Minubo_Interface_Model_Read_Common

{
    /**
     * Concrete implementation of abstract method to export given orders to csv file in var/export.
     *
     * @param $orders List of orders of type Mage_Sales_Model_Order or order ids to export.
     * @return String The name of the written csv file in var/export
     */
    public function read($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id)
    {
    	return $this->readOrders($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
    }

    public function readOrders($lastExportDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id)
    {
		// Bestellungen laden
		if(!$lastExportDate) {
			$orders = Mage::getModel('sales/order')->getCollection();
		} else {
			$orders = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('updated_at',Array('gt'=>$lastExportDate));
		}
		
		if($debug) echo '# Recordcount: '.$orders->getSize().'<br>';

		// CSV-Header
		//echo '"Name, Vorname","Strasse","Kto","BLZ","Kontoinhaber"' . "\n";

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

		if($debug) echo '# Paramter: '.$lastExportDate.'/'.$maxChangeDate.'/'.$lastOrderID.'/'.$maxOrderID.'/'.$limit.'/'.$offset.'<br>';
		$i=0;
		$firstChangeDate='';
		$lastChangeDate='';
		foreach($orders as $key=>$order){
			//product
			if($debug) echo '# Next order: '.$lastExportDate.'/'.$maxChangeDate.'/'.$firstChangeDate.'/'.$lastChangeDate.'/'.$order->getUpdatedAt().'<br>';
			if(($lastOrderID && ($order->getIncrementId()<=$lastOrderID)) || !$order->getIncrementId()) {
				if($debug) echo '# Deleted by ID (<=): '.$key.' '.$order->getIncrementId().' '.$order->getUpdatedAt(),'<br>';
				$orders->removeItemByKey($key);
			} elseif(($maxOrderID && ($order->getIncrementId()>$maxOrderID)) || !$order->getIncrementId()) {
				if($debug) echo '# Deleted by ID (>): '.$key.' '.$order->getIncrementId().' '.$order->getUpdatedAt(),'<br>';
				$orders->removeItemByKey($key);
			} else {
				$i++;
				if($lastExportDate && ($order->getUpdatedAt()<=$lastExportDate)) {
					$orders->removeItemByKey($key);
					if($debug) echo '# Deleted by LastExportDate(<=): '.$key.' '.$order->getIncrementId().' '.$order->getUpdatedAt(),'<br>';
				} elseif($maxChangeDate && ($order->getUpdatedAt()>$maxChangeDate)) {
					$orders->removeItemByKey($key);
					if($debug) echo '# Deleted by MAXDATE (>): '.$key.' '.$order->getIncrementId().' '.$order->getUpdatedAt(),'<br>';
				} else {
					if(($offset==0) && (!$firstChangeDate || ($order->getUpdatedAt()<$firstChangeDate))) {
						if($debug) echo '# New FirstChangeDate: '.$order->getUpdatedAt().' -> '.$firstChangeDate.'<br>';
						$firstChangeDate=$order->getUpdatedAt();
					} 
					if(!$maxChangeDate && (!$lastChangeDate || ($order->getUpdatedAt()>$lastChangeDate))) {
						if($debug) echo '# New LastChangeDate: '.$order->getUpdatedAt().' -> '.$lastChangeDate.'<br>';
						$lastChangeDate=$order->getUpdatedAt();
					}
				
					if($i<=$offset) {
						$orders->removeItemByKey($key);
						if($debug) echo '# Deleted by OFFSET: '.$key.' '.$order->getIncrementId().' '.$order->getUpdatedAt(),'<br>';
					}
					if($i>($offset+$limit)) {
						$orders->removeItemByKey($key);
						if($debug) echo '# Deleted by LIMIT: '.$key.' '.$order->getIncrementId().' '.$order->getUpdatedAt(),'<br>';
					}
				}
			}
    	}
		if($debug) echo '# New Values: '.$firstChangeDate.'/'.$lastChangeDate.'<br>';
			
		if(!$maxChangeDate) {
			$config = new Mage_Core_Model_Config();
			$config->saveConfig('minubo_interface/settings/firstchangedate', $firstChangeDate, 'default', 0);
			$config->saveConfig('minubo_interface/settings/lastchangedate', $lastChangeDate, 'default', 0);
		}
		
    	return $orders;
    }
    
    public function readCountries($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id)
    {
		$countries = Mage::getModel('directory/country')->getResourceCollection()->loadByStore()->toOptionArray(true);
		return $countries;
    }
    
    public function readCustomers($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id)
    {
		// $customers = Mage::getModel('customer/customer')->getCollection()->addAttributeToSelect('*');
		$customers = Mage::getModel('customer/customer')->getResourceCollection()->toOptionArray(true);
		return $customers;
    }
    
    public function readProducts($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id)
    {
		// $products = Mage::getModel('catalog/product')->getResourceCollection()->toOptionArray(true);
		$products = Mage::getResourceModel('catalog/product_collection');
		
		$i=0;
		$firstChangeDate='';
		$lastChangeDate='';
		foreach($products as $key=>$product){
			if($i<=$offset) {
				$products->removeItemByKey($key);
				if($debug) echo '# Deleted by OFFSET: '.$key.' '.$products->getIncrementId().' '.$products->getUpdatedAt(),'<br>';
			}
			if($i>($offset+$limit)) {
				$products->removeItemByKey($key);
				if($debug) echo '# Deleted by LIMIT: '.$key.' '.$products->getIncrementId().' '.$products->getUpdatedAt(),'<br>';
			}
    	}
    	
		return $products;
    }

}

?>