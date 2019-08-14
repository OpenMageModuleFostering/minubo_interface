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
class Minubo_Interface_Model_Read_Products extends Minubo_Interface_Model_Read_Common
{
    protected function _construct()
    {
        $this->_init('minuboface/categories');
    }

    /**
     * Concrete implementation of abstract method to export given products to csv file in var/export.
     *
     * @param $products List of Products of type Mage_Sales_Model_Products or product ids to export.
     * @return String The name of the written csv file in var/export
     */
    public function read($lastChangeDate, $lastOrderID, $maxOrderID, $limit)
    {

			// Producte laden
			$products = Mage::getModel('catalog/product')->getCollection();

			$i=0;
			foreach($products as $key=>$product){
       //product
        if($product->getUpdatedAt()<=$lastChangeDate) {
        	// echo $key.' '.$product->getIncrementId().' '.$product->getUpdatedAt(),'<br>';
        	$products->removeItemByKey($key);
        } else {
        	$i++;
        	if($i>$limit) $products->removeItemByKey($key);
        }
    	}

      return $products;
    }

    public function readCategories($lastChangeDate, $lastOrderID, $maxOrderID, $limit)
    {

		// $cat = Mage::getModel('minubo_interface/categories');
    echo("Loading the categories with an ID of 2");
    $cat = $this->load('2');
    $data = $cat->getData();
    var_dump($data);


			// Producte laden
			// $categories = Mage::getModel('catalog/category')->getCollection();
			// $helper     = Mage::helper('catalog/category');
    	// $categories = $helper->getStoreCategories('name', true, false);
    	// $categories      = $helper->getStoreCategories('name', false, false);

    	// $table = $this->getMainTable();
      // $where = $this->_getReadAdapter()->quoteInto("id = ?", 123);
			// $select = $this->_getReadAdapter()->select()->from("catalog_category_flat_store_1")->columns(array('entity_id', 'parent_id', 'name'))->where($where)->limit(10,5)->order('created_time')->group('list_id')->having('list_id > ?',10);
			// $select = $this->_getReadAdapter()->select()->from("catalog_category_flat_store_1")->columns(array('entity_id', 'parent_id', 'name'))->order('entity_id');
			// echo $select;

// print_r($categories);
			// return $categories;
    }

    public function readRegions($lastChangeDate, $lastOrderID, $maxOrderID, $limit)
    {

			// $regions = Mage::getModel('directory/region')->getCollection();
			// $regions = Mage::getModel('directory/region')->getResourceCollection()->toOptionArray(true);
      $regions = Mage::getResourceModel('directory/region_collection')->load();
			// print_r($regions);

      return $regions;
    }

    public function readCountries($lastChangeDate, $lastOrderID, $maxOrderID, $limit)
    {

			// $countries = Mage::getModel('directory/country')->getCollection();
			$countries = Mage::getModel('directory/country')->getResourceCollection()->loadByStore()->toOptionArray(true);

      return $countries;
    }
}
?>