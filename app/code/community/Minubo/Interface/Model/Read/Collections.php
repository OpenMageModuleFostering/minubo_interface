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
 * @author     Sven Rothe <sven@minubo.com>
 * */

class Minubo_Interface_Model_Read_Collections extends Minubo_Interface_Model_Read_Common

{
    /**
     * Concrete implementation of abstract method to export given orders to csv file in var/export.
     *
     * @param $lastChangeDate
     * @param $maxChangeDate
     * @param $lastOrderID
     * @param $maxOrderID
     * @param $limit
     * @param $offset
     * @param $debug
     * @param $pdata
     * @param $store_id
     * @param $type
     * @param $sort
     * @return String The name of the written csv file in var/export
     */
    public function read($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id, $type='', $sort='')
    {
    	return $this->readCountries($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
    }

    public function readCountries($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id)
    {
		$countries = Mage::getModel('directory/country')->getResourceCollection()->loadByStore(); // ->toOptionArray(true);
		// $countries = Mage::getModel('directory/country_api')->items();
		return $countries;
    }

}

?>