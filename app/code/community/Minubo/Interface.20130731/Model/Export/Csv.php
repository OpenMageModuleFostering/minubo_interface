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

class Minubo_Interface_Model_Export_Csv extends Minubo_Interface_Model_Export_Abstractcsv

{
    const ENCLOSURE = '"';
    const DELIMITER = ';';

    /**
     * Concrete implementation of abstract method to export given orders to csv file in var/export.
     *
     * @param $orders List of orders of type Mage_Sales_Model_Order or order ids to export.
     * @return String The name of the written csv file in var/export
     */
    public function exportOrders($orders)
    {
        $fileName = 'order_export_'.date("Ymd_His").'.txt';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, 'orders');
        foreach ($orders as $order) {
        	// wenn ID übergeben wurde: $order = Mage::getModel('sales/order')->load($order);
          $this->writeOrder($order, $fp, 'orders');
        }

        fclose($fp);

        return $fileName;
    }
    public function exportOrderCustomers($orders)
    {
        $fileName = 'ordercust_export_'.date("Ymd_His").'.txt';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, 'orderCustomers');
        foreach ($orders as $order) {
        	// wenn ID übergeben wurde: $order = Mage::getModel('sales/order')->load($order);
          $this->writeOrder($order, $fp, 'orderCustomers');
        }

        fclose($fp);

        return $fileName;
    }
    public function exportOrderItems($orders)
    {
        $fileName = 'orderitem_export_'.date("Ymd_His").'.txt';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, 'orderItems');
        foreach ($orders as $order) {
        	// wenn ID übergeben wurde: $order = Mage::getModel('sales/order')->load($order);
          $this->writeOrder($order, $fp, 'orderItems');
        }

        fclose($fp);

        return $fileName;
    }
    public function exportOrderProducts($orders)
    {
        $fileName = 'orderproduct_export_'.date("Ymd_His").'.txt';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, 'orderProducts');
        foreach ($orders as $order) {
        	// wenn ID übergeben wurde: $order = Mage::getModel('sales/order')->load($order);
          $this->writeOrder($order, $fp, 'orderProducts');
        }

        fclose($fp);

        return $fileName;
    }
    public function exportProducts($products)
    {
        $fileName = 'product_export_'.date("Ymd_His").'.txt';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, 'products');
        foreach ($products as $product) {
        	$this->writeProduct($product, $fp, 'products');
        }

        fclose($fp);

        return $fileName;
    }
		public function exportCategories($categories)
    {
        $fileName = 'category_export_'.date("Ymd_His").'.txt';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, 'categories');
        foreach ($categories as $category) {
        	$this->writeProduct($category, $fp, 'categories');
        }

        fclose($fp);

        return $fileName;
    }
    public function exportCountries($countries)
    {
        $fileName = 'country_export_'.date("Ymd_His").'.txt';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, 'countries');
        foreach ($countries as $country) {
        	$this->writeDirectory($country, $fp, 'countries');
        }

        fclose($fp);

        return $fileName;
    }
    public function exportRegions($regions)
    {
        $fileName = 'region_export_'.date("Ymd_His").'.txt';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, 'regions');
        foreach ($regions as $region) {
        	$this->writeDirectory($region, $fp, 'regions');
        }

        fclose($fp);

        return $fileName;
    }

    /**
	 * Writes the head row with the column names in the csv file.
	 *
	 * @param $fp The file handle of the csv file
	 */
    protected function writeHeadRow($fp, $group)
    {
        fputcsv($fp, $this->getHeadRowValues($group), self::DELIMITER, self::ENCLOSURE);
    }

    /**
	 * Writes the row(s) for the given order in the csv file.
	 * A row is added to the csv file for each ordered item.
	 *
	 * @param Mage_Sales_Model_Order $order The order to write csv of
	 * @param $fp The file handle of the csv file
	 */
    protected function writeOrder($order, $fp, $group)
    {
        $common = $this->getCommonOrderValues($order, $group);

				switch($group){
					case 'orders':
						fputcsv($fp, $common, self::DELIMITER, self::ENCLOSURE);
						break;
					case 'orderCustomers':
						fputcsv($fp, $common, self::DELIMITER, self::ENCLOSURE);
						break;
					case 'orderItems':
		        $orderItems = $order->getItemsCollection();
		        $itemInc = 0;
		        foreach ($orderItems as $item)
		        {
		            if (!$item->isDummy()) {
		                $record = array_merge($common, $this->getOrderItemValues($item, $order, ++$itemInc));
		                fputcsv($fp, $record, self::DELIMITER, self::ENCLOSURE);
		            }
		        }
						break;
					case 'orderProducts':
		        $orderProducts = $order->getItemsCollection();
		        $itemInc = 0;
		        foreach ($orderProducts as $item)
		        {
		            if (!$item->isDummy()) {
		                $record = array_merge($common, $this->getOrderItemValues($item, $order, ++$itemInc));
		                fputcsv($fp, $record, self::DELIMITER, self::ENCLOSURE);
		            }
		        }
						break;
				}
    }

	protected function writeProduct($data, $fp, $group)
    {
        switch($group){
					case 'products':
						//$record = $this->getProductValues($product, ++$itemInc);
		        fputcsv($fp, $data, self::DELIMITER, self::ENCLOSURE);
		        break;
		      case 'categories':
		      	// $record = $this->getCategoryValues($product);
		        fputcsv($fp, $data, self::DELIMITER, self::ENCLOSURE);
		      	break;
				}
    }

	protected function writeDirectory($entries, $fp, $group)
    {
        switch($group){
					case 'countries':
						//print_r($directory);
						//$record = $this->getProductValues($product, ++$itemInc);
		        fputcsv($fp, $entries, self::DELIMITER, self::ENCLOSURE);
		        break;
					case 'regions':
						// print_r($entries);
						// $record = $this->getProductValues($product, ++$itemInc);
		        fputcsv($fp, $entries, self::DELIMITER, self::ENCLOSURE);
		        break;
						$i=0;
		        foreach ($entries as $item)
		        {
		            // $record = array($item->getData('region_id'), $item->getData('country_id'), $item->getData('code'), $item->getData('default_name'));
		            // fputcsv($fp, $record, self::DELIMITER, self::ENCLOSURE);
		            // if (!$item->isDummy()) {
		            	$i++; echo $i.': '; print_r($item); echo '<br>';
		            	// die('stop');
		            // }
		        }

		        break;
				}
    }


    /**
	 * Returns the head column names.
	 *
	 * @return Array The array containing all column names
	 */
    protected function getHeadRowValues($group)
    {
    	$r = array();
    	switch($group){
    		case 'orders':
    			$r = array(
						'Order Number',
						'Order Date',
						'Order Changed',
						'Order Status',
						'Order Purchased From',
						'Order Payment Method',
						'Credit Card Type',
						'Order Shipping Method',
						'Order Subtotal',
						'Order Tax',
						'Order Shipping',
						'Order Discount',
						'Order Grand Total',
						'Order Base Grand Total',
						'Order Paid',
						'Order Refunded',
						'Order Due',
						'Total Qty Items Ordered'
		    	);
    			break;
    		case 'orderCustomers':
    			$r = array(
						'Order Number',
						'Order Date',
						'Order Changed',
						'Customer Name',
						'Customer Email',
						'Shipping Name',
						'Shipping Company',
						'Shipping Street',
						'Shipping Zip',
						'Shipping City',
						'Shipping State',
						'Shipping State Name',
						'Shipping Country',
						'Shipping Country Name',
						'Shipping Phone Number',
						'Billing Name',
						'Billing Company',
						'Billing Street',
						'Billing Zip',
						'Billing City',
						'Billing State',
						'Billing State Name',
						'Billing Country',
						'Billing Country Name',
						'Billing Phone Number'
		    	);
    			break;
    		case 'orderItems':
    			$r = array(
						'Order Number',
						'Order Date',
						'Order Changed',
						'Order Item Increment',
						'Item Name',
						'Item Status',
						'Item SKU',
						'Item Options',
						'Item Original Price',
						'Item Price',
						'Item Qty Ordered',
						'Item Qty Invoiced',
						'Item Qty Shipped',
						'Item Qty Canceled',
						'Item Qty Refunded',
						'Item Tax',
						'Item Discount',
						'Item Total'
		    	);
    			break;
    		case 'orderProducts':
    			$r = array(
						'Item Name',
						'Item Status',
						'Item SKU',
						'Item Options',
						'Item Original Price',
						'Item Price',
						'Item Tax'
		    	);
    			break;
    		case 'products':
    			$r = array(
						'entity_id','name','sku','sku_type','computer_manufacturers','computer_manufacturers_value','country_orgin','color','color_value','price','tax_class_id','cost','msrp','msrp_display_actual_price_type','msrp_enabled','shoe_size','shoe_size_value'
		    	);
    			break;
    		case 'categories':
    			$r = array(
    				'Category Id',
    				'Parent Id',
    				'Position',
    				'Category Name'
    			);
    			break;
    		case 'regions':
					$r = array (
						'Region Id',
						'Country Id',
						'Region Code',
						'Region Name'
					);
					break;
				case 'countries':
					$r = array (
						'Country Id',
						'Country Name'
					);
					break;
    	}
      return $r;
    }

    /**
	 * Returns the values which are identical for each row of the given order. These are
	 * all the values which are not item specific: order data, shipping address, billing
	 * address and order totals.
	 * Magento-DateFormat (DE): $order->getCreatedAt(), 'medium', true)
	 *
	 * @param Mage_Sales_Model_Order $order The order to get values from
	 * @return Array The array containing the non item specific values
	 */
    protected function getCommonOrderValues($order, $group)
    {
    	switch($group){
    		case 'orders':
    			return array(
            $order->getRealOrderId(),
            $order->getCreatedAt(),
            $order->getUpdatedAt(),
            $order->getStatus(),
            $this->getStoreName($order),
            $this->getPaymentMethod($order),
            $this->getCcType($order),
            $this->getShippingMethod($order),
            $this->formatPrice($order->getData('subtotal'), $order),
            $this->formatPrice($order->getData('tax_amount'), $order),
            $this->formatPrice($order->getData('shipping_amount'), $order),
            $this->formatPrice($order->getData('discount_amount'), $order),
            $this->formatPrice($order->getData('grand_total'), $order),
            $this->formatPrice($order->getData('base_grand_total'), $order),
            $this->formatPrice($order->getData('total_paid'), $order),
            $this->formatPrice($order->getData('total_refunded'), $order),
            $this->formatPrice($order->getData('total_due'), $order),
            $this->getTotalQtyItemsOrdered($order));
    			break;
    		case 'orderCustomers':
	        $shippingAddress = !$order->getIsVirtual() ? $order->getShippingAddress() : null;
	        $billingAddress = $order->getBillingAddress();
	        return array(
            $order->getRealOrderId(),
            $order->getCreatedAt(),
            $order->getUpdatedAt(),
            $order->getCustomerName(),
            $order->getCustomerEmail(),
            $shippingAddress ? $shippingAddress->getName() : '',
            $shippingAddress ? $shippingAddress->getData("company") : '',
            $shippingAddress ? $this->getStreet($shippingAddress) : '',
            $shippingAddress ? $shippingAddress->getData("postcode") : '',
            $shippingAddress ? $shippingAddress->getData("city") : '',
            $shippingAddress ? $shippingAddress->getRegionCode() : '',
            $shippingAddress ? $shippingAddress->getRegion() : '',
            $shippingAddress ? $shippingAddress->getCountry() : '',
            $shippingAddress ? $shippingAddress->getCountryModel()->getName() : '',
            $shippingAddress ? $shippingAddress->getData("telephone") : '',
            $billingAddress->getName(),
            $billingAddress->getData("company"),
            $this->getStreet($billingAddress),
            $billingAddress->getData("postcode"),
            $billingAddress->getData("city"),
            $billingAddress->getRegionCode(),
            $billingAddress->getRegion(),
            $billingAddress->getCountry(),
            $billingAddress->getCountryModel()->getName(),
            $billingAddress->getData("telephone"));
          break;
      }
      return array(
        $order->getRealOrderId(),
        $order->getCreatedAt(),
        $order->getUpdatedAt());
    }

    /**
	 * Returns the item specific values.
	 *
	 * @param Mage_Sales_Model_Order_Item $item The item to get values from
	 * @param Mage_Sales_Model_Order $order The order the item belongs to
	 * @return Array The array containing the item specific values
	 */
    protected function getOrderItemValues($item, $order, $itemInc=1)
    {

        return array(
            $itemInc,
            $item->getName(),
            $item->getStatus(),
            $this->getItemSku($item),
            $this->getItemOptions($item),
            $this->formatPrice($item->getOriginalPrice(), $order),
            $this->formatPrice($item->getData('price'), $order),
            (int)$item->getQtyOrdered(),
            (int)$item->getQtyInvoiced(),
            (int)$item->getQtyShipped(),
            (int)$item->getQtyCanceled(),
        		(int)$item->getQtyRefunded(),
            $this->formatPrice($item->getTaxAmount(), $order),
            $this->formatPrice($item->getDiscountAmount(), $order),
            $this->formatPrice($this->getItemTotal($item), $order)
        );
    }

    protected function getProductValues($product, $itemInc=1)
    {

        return array(
            $itemInc,
            $item->getName(),
            $item->getStatus(),
            $this->getItemSku($item),
            $this->getItemOptions($item),
            $this->formatPrice($item->getOriginalPrice(), $order),
            $this->formatPrice($item->getData('price'), $order),
            (int)$item->getQtyOrdered(),
            (int)$item->getQtyInvoiced(),
            (int)$item->getQtyShipped(),
            (int)$item->getQtyCanceled(),
        		(int)$item->getQtyRefunded(),
            $this->formatPrice($item->getTaxAmount(), $order),
            $this->formatPrice($item->getDiscountAmount(), $order),
            $this->formatPrice($this->getItemTotal($item), $order)
        );
    }

    protected function getCategoryValues($category)
    {

        return array(
            $category['entity_id'],
            $category['parent_id'],
            $category['position'],
            $category['name']
        );
    }
}

?>