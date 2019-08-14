<?php
/**
 *
 * @category   Magento
 * @package    Minubo_Interface
 * @copyright  Copyright (c) 2013 Minubo (http://www.minubo.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class Minubo_Interface_Model_Observer
{
	public function addMassAction($observer) {
   		$block = $observer->getEvent()->getBlock();
        if(get_class($block) =='Mage_Adminhtml_Block_Widget_Grid_Massaction'
            && strstr( $block->getRequest()->getControllerName(), 'sales_order') && Mage::getStoreConfig("order_export/export_orders/active"))
        {
        	$block->addItem('orderexport', array(
                'label' => Mage::helper('sales')->__('Export Orders'),
                'url' => Mage::app()->getStore()->getUrl('*/sales_order_export/csvexport'),
            ));

        }


   }

}