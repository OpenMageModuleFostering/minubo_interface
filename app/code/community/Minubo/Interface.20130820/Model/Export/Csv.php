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
    public function exportOrderCustomers($orders, $pdata)
    {
        $fileName = 'ordercust_export_'.date("Ymd_His").'.txt';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, 'orderCustomers', $pdata);
        foreach ($orders as $order) {
        	// wenn ID übergeben wurde: $order = Mage::getModel('sales/order')->load($order);
        	$this->writeOrder($order, $fp, 'orderCustomers', $pdata);
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
    public function exportProducts($products)
    {
    	$skipCols = array('description', 'in_depth', 'activation_information');
    	echo "products";
        $fileName = 'product_export_'.date("Ymd_His").'.txt';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');
        $this->writeHeadRow($fp, 'products', '', $products[0], $skipCols);
        foreach ($products as $product) {
        	// wenn ID übergeben wurde: $order = Mage::getModel('sales/order')->load($order);
          	$this->writeCollection($product, $fp, 'products', $skipCols);
        }

        fclose($fp);

        return $fileName;
    }
    
    
    
    public function exportCustomers($customers, $pdata)
    {
        $fileName = 'customer_export_'.date("Ymd_His").'.txt';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, 'customers', $pdata);
        foreach ($customers as $customer) {
        	// wenn ID übergeben wurde: $order = Mage::getModel('sales/order')->load($order);
          	$this->writeCollection($customer, $fp, 'customers', $pdata);
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
        	$this->writeCollection($country, $fp, 'countries');
        }

        fclose($fp);

        return $fileName;
    }
    
    
    public function exportProducts2($products)
    {
        $fileName = 'product_export_'.date("Ymd_His").'.txt';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, 'products');
        foreach ($products as $product) {
        	fputcsv($fp, $product, self::DELIMITER, self::ENCLOSURE);
        }

        fclose($fp);

        return $fileName;
    }
		public function exportCategories($categories)
    {
    		$renameCols = array('entity_id' => 'category_id');
    	
        $fileName = 'category_export_'.date("Ymd_His").'.txt';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, 'categories', '', $categories[0], Array(), $renameCols);
        foreach ($categories as $category) {
        	fputcsv($fp, $category, self::DELIMITER, self::ENCLOSURE);
        }

        fclose($fp);

        return $fileName;
    }
    public function exportProductcategories($productcategories)
    {
        $skipCols = array('is_parent');
    	
    		$fileName = 'productcategory_export_'.date("Ymd_His").'.txt';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, '', '', $productcategories[0], $skipCols);
        
        foreach ($productcategories as $productcategory) {
        	$this->writeCollection($productcategory, $fp, '', $skipCols);
        }

        fclose($fp);

        return $fileName;
    }
    public function exportProductattributes($productattributes)
    {
    		$renameCols = array('attribute_set_id' => 'setKey', 
    												'attribute_set_name' => 'setName', 
    												'attribute_id' => 'attributeKey', 
    												'attribute_code' => 'attributeCode', 
    												'backend_type' => 'attributeType', 
    												'is_required' => 'attributeRequired', 
    												'value' => 'optionLabel', 
    												'value_id' => 'optionValue');
    	
        $fileName = 'productattributes_export_'.date("Ymd_His").'.txt';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, 'productattributes', '', $productattributes[0], Array(), $renameCols);
        foreach ($productattributes as $productattribute) {
        	fputcsv($fp, $productattribute, self::DELIMITER, self::ENCLOSURE);
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
        	fputcsv($fp, $region, self::DELIMITER, self::ENCLOSURE);
        }

        fclose($fp);

        return $fileName;
    }
    

    /**
	 * Writes the head row with the column names in the csv file.
	 *
	 * @param $fp The file handle of the csv file
	 */
    protected function writeHeadRow($fp, $group, $pdata='', $cols=array(), $skipCols=array(), $renameCols=array())
    {
        fputcsv($fp, $this->getHeadRowValues($group, $pdata, $cols, $skipCols, $renameCols), self::DELIMITER, self::ENCLOSURE);
    }

    /**
	 * Writes the row(s) for the given order in the csv file.
	 * A row is added to the csv file for each ordered item.
	 *
	 * @param Mage_Sales_Model_Order $order The order to write csv of
	 * @param $fp The file handle of the csv file
	 */
    protected function writeOrder($order, $fp, $group, $pdata='')
    {
        $common = $this->getCommonOrderValues($order, $group, $pdata);

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
			case 'orderItems2':
				$orderItems = $order->getAllVisibleItems();
				$itemInc = 0;
				foreach ($orderItems as $item)
				{
					if (!$item->isDummy()) {
						$record = array_merge($common, $this->getOrderItemValues($item, $order, ++$itemInc));
						fputcsv($fp, $record, self::DELIMITER, self::ENCLOSURE);
					}
				}
				break;
			case 'products2':
				$products = $order->getItemsCollection();
				$itemInc = 0;
				foreach ($products as $product)
				{
					if (!$product->isDummy()) {
						$record = array_merge($this->getOrderItemValues($product, $order));
						fputcsv($fp, $record, self::DELIMITER, self::ENCLOSURE);
					}
				}
				break;
		}
    }

	protected function writeCollection($entries, $fp, $group, $skipCols=array())
    {
        if(count($skipCols)>0) {
			$data = array();
			foreach ($entries as $col => $value) {
				if(!in_array($col, $skipCols)) $data[$col] = str_replace(chr(13).chr(10),' ',str_replace('"',"'",$value));
			}
			fputcsv($fp, $data, self::DELIMITER, self::ENCLOSURE);
		} else {
			$data = array();
			foreach ($entries as $col => $value) {
				$data[$col] = str_replace(chr(13).chr(10),' ',str_replace('"',"'",$value));
			}	
			//$record = $this->getProductValues($product, ++$itemInc);
			fputcsv($fp, $data, self::DELIMITER, self::ENCLOSURE);
		}
    }


    /**
	 * Returns the head column names.
	 *
	 * @return Array The array containing all column names
	 */
    protected function getHeadRowValues($group, $pdata='', $cols=array(), $skipCols=array(), $renameCols=array())
    {
    	$r = array();
    	switch($group){
    		case 'orders':
    			$r = array(
						'Order ID',
						'Order Number',
						'Order Date',
						'Order Changed',
						'Customer Number',
						'Order Status',
						'Order State',
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
						'Total Qty Items Ordered',
						'Shipping Zip',
						'Shipping City',
						'Shipping Country'
		    	);
    			break;
    		case 'orderCustomers':
    			if(!$pdata) {
    				$r = array(
						'Order ID',
						'Order Number',
						'Order Date',
						'Order Changed',
						'Customer Number',
						'Customer Group',
						'Customer Group Name',
						'Customer Gender',
						'Shipping Gender',
						'Shipping Zip',
						'Shipping City',
						'Shipping State',
						'Shipping State Name',
						'Shipping Country',
						'Shipping Country Name',
						'Billing Gender',
						'Billing Zip',
						'Billing City',
						'Billing State',
						'Billing State Name',
						'Billing Country',
						'Billing Country Name'
		    		);
		    	} else {
		    		$r = array(
						'Order ID',
						'Order Number',
						'Order Date',
						'Order Changed',
						'Customer Number',
						'Customer Group',
						'Customer Group Name',
						'Customer Gender',
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
						'Shipping Gender',
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
						'Billing Gender',
						'Billing Phone Number'
		    		);
		    	}
    			break;
    		case 'orderItems':
    			$r = array(
						'Order ID',
						'Order Number',
						'Order Date',
						'Order Changed',
						'Order Item Increment',
						'Item ID',
						'Item SKU',
						'Item Name',
						'Item Status',
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
    		case 'customers':
    			if(!$pdata) {
    				$r = array(
						'Customer Number',
						'Customer Group',
						'Customer Group Name',
						'Customer Gender',
						'Customer Name',
						'Customer Email',
						'Customer Company',
						'Customer Street',
						'Customer Zip',
						'Customer City',
						'Customer State',
						'Customer State Name',
						'Customer Country',
						'Customer Country Name',
						'Customer Phone Number'
		    		);
				} else {
		    		$r = array(
						'Customer Number',
						'Customer Gender',
						'Customer Zip',
						'Customer City',
						'Customer State',
						'Customer State Name',
						'Customer Country',
						'Customer Country Name'
		    		);
		    	}    			
		    	break;
    		case 'products':
    			if($cols) {
					foreach ($cols as $col => $val) if(!in_array($col, $skipCols)) $r[] = $col;
				} else {
	    			$r = array(
						'entity_id','name','sku','sku_type','computer_manufacturers','computer_manufacturers_value','country_orgin','color','color_value','price','tax_class_id','cost','msrp','msrp_display_actual_price_type','msrp_enabled','shoe_size','shoe_size_value'
		    		);
		    	}
    			break;
    		case 'categories':
    			if($cols) {
					foreach ($cols as $col => $val) if(!in_array($col, $skipCols)) { if(array_key_exists($col, $renameCols)) $r[] = $renameCols[$col]; else $r[] = $col; }
				} else {
					$r = array(
    					'Category Id',
    					'Parent Id',
    					'Position',
    					'Category Name',
    					'level',
    					'image',
    					'url_key', 
    					'url_path'
    				);
    			}
    			break;
    		case 'productcategories':
    			$r = array(
    				'category_id','product_id','store_id','position','visibility'
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
				default:
					if($cols) {
						foreach ($cols as $col => $val) if(!in_array($col, $skipCols)) { if(array_key_exists($col, $renameCols)) $r[] = $renameCols[$col]; else $r[] = $col; }
					}
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
    protected function getCommonOrderValues($order, $group, $pdata='')
    {
    	switch($group) {
    		case 'orders':
    			$shippingAddress = !$order->getIsVirtual() ? $order->getShippingAddress() : null;
				$billingAddress = $order->getBillingAddress();
				return array(
					$order->getId(),
					$order->getRealOrderId(),
					$order->getCreatedAt(),
					$order->getUpdatedAt(),
					$order->getCustomerId(),
					$order->getStatus(),
					$order->getState(),
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
					$this->getTotalQtyItemsOrdered($order),
					$shippingAddress ? $shippingAddress->getData("postcode") : $billingAddress->getData("postcode"),
					$shippingAddress ? $shippingAddress->getData("city") : $billingAddress->getData("city"),
					$shippingAddress ? $shippingAddress->getCountry() : $billingAddress->getCountry());
    			break;
    		case 'orderCustomers':
				$shippingAddress = !$order->getIsVirtual() ? $order->getShippingAddress() : null;
				$billingAddress = $order->getBillingAddress();
				$group = Mage::getModel('customer/group')->load($order->getCustomerGroupId());
				if(!$pdata) {
					return array(
						$order->getId(),
						$order->getRealOrderId(),
						$order->getCreatedAt(),
						$order->getUpdatedAt(),
						$order->getCustomerId(),
						$order->getCustomerGroupId(),
						$group->getCode(),
						$order->getCustomerGender(),
						$shippingAddress ? $shippingAddress->getData("postcode") : '',
						$shippingAddress ? $shippingAddress->getData("city") : '',
						$shippingAddress ? $shippingAddress->getRegionCode() : '',
						$shippingAddress ? $shippingAddress->getRegion() : '',
						$shippingAddress ? $shippingAddress->getCountry() : '',
						$shippingAddress ? $shippingAddress->getCountryModel()->getName() : '',
						$billingAddress->getData("gender"),
						$billingAddress->getData("postcode"),
						$billingAddress->getData("city"),
						$billingAddress->getRegionCode(),
						$billingAddress->getRegion(),
						$billingAddress->getCountry(),
						$billingAddress->getCountryModel()->getName());
				} else {
					return array(
						$order->getId(),
						$order->getRealOrderId(),
						$order->getCreatedAt(),
						$order->getUpdatedAt(),
						$order->getCustomerId(),
						$order->getCustomerGroupId(),
						$group->getCode(),
						$order->getCustomerGender(),
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
						$shippingAddress ? $shippingAddress->getData("gender") : '',
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
						$billingAddress->getData("gender"),
						$billingAddress->getData("telephone"));
				}
          		break;
      }
      return array(
        $order->getId(),
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
				$product = Mage::getModel('catalog/product')->loadByAttribute('sku', $this->getItemSku($item));
						
        return array(
            $itemInc,
            $product->getId(),
            $this->getItemSku($item),
            $item->getName(),
            $item->getStatus(),
            $this->getItemOptions($item),
            $this->formatPrice($item->getOriginalPrice(), $order),
            $this->formatPrice($item->getData('price'), $order),
            (float)$item->getQtyOrdered(),
            (float)$item->getQtyInvoiced(),
            (float)$item->getQtyShipped(),
            (float)$item->getQtyCanceled(),
        		(float)$item->getQtyRefunded(),
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