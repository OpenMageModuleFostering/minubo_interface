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
    	exportOrder($orders,'order','orders','');
    }
    
    public function exportOrder($rows, $filename, $type, $pdata = '')
    {
        $fileName = $filename.'_export_'.date("Ymd_His").'.csv';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, $type, $pdata);
        foreach ($rows as $row) {
        	$this->writeOrder($row, $fp, $type, $pdata);
        }

        fclose($fp);

        return $fileName;
    }
    
    public function exportCollection($rows, $filename, $type, $pdata = '')
    {
        $fileName = $filename.'_export_'.date("Ymd_His").'.csv';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, $type, $pdata);
        foreach ($rows as $row) {
        	$this->writeCollection($row, $fp, $type);
        }

        fclose($fp);

        return $fileName;
    }
    
    public function exportTable($rows, $filename, $type, $colTitles = Array(), $skipCols = Array(), $renameCols = Array())
    {
        $fileName = $filename.'_export_'.date("Ymd_His").'.csv';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');
				
        $this->writeHeadRow($fp, $type, '', $colTitles, $skipCols, $renameCols);
        foreach ($rows as $row) {
        	if (count($skipCols)==0) {
	        	fputcsv($fp, $row, self::DELIMITER, self::ENCLOSURE);
	        } else {
	        	$this->writeCollection($row, $fp, $type, $skipCols);
	        }
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
						'Order_ID',
						'Order_Number',
						'Order_Date',
						'Order_Changed',
						'Customer_Number',
						'Credit_Card_Type',
						'Shipping_Zip',
						'Shipping_City',
						'Shipping_Country',
						'Customer_HashCode',
						'parentKey', 'isActive', 'status', 'comment',
						'shippingAddressIncrementId', 'shippingAddressParentId', 
						'shippingAddressRegionId','storeId','billingAddressId','shippingAddressId','quoteId',
						'isVirtual','customerGroupId','customerIsGuest','shippingAddressAddressId',
						'shippingAddressCreatedAt','shippingAddressUpdatedAt','taxAmount','shippingAmount','discountAmount',
						'subtotal','grandTotal','totalPaid','totalRefunded','totalQtyOrdered','totalCanceled','totalInvoiced',
						'baseTaxAmount','baseShippingAmount','baseDiscountAmount','baseGrandTotal','baseSubtotal',
						'baseTotalPaid','baseTotalRefunded','baseTotalQtyOrdered','baseTotalCanceled','baseTotalInvoiced',
						'storeToBaseRate','storeToOrderRate','baseToGlobalRate','baseToOrderRate','isActive','storeName',
						'status','state','appliedRuleIds','globalCurrencyCode','baseCurrencyCode','storeCurrencyCode',
						'orderCurrencyCode','shippingMethod','shippingDescription','giftMessageId','shippingAddressIsActive',
						'shippingAddressAddressType','shippingAddressRegion','paymentMethod'
		    	);
    			break;
    		case 'orderCustomers':
    			if(!$pdata) {
    				$r = array(
						'Order_ID',
						'Order_Number',
						'Order_Date',
						'Order_Changed',
						'Customer_Number',
						'Customer_Group',
						'Customer_Group_Name',
						'Customer_Gender',
						'Shipping_Zip',
						'Shipping_City',
						'Shipping_State',
						'Shipping_State_Name',
						'Shipping_Country',
						'Shipping_Country_Name',
						'Shipping_Gender',
						'Billing_Zip',
						'Billing_City',
						'Billing_State',
						'Billing_State_Name',
						'Billing_Country',
						'Billing_Country_Name',
						'Billing_Gender',
						'Customer_HashCode'
		    		);
		    	} else {
		    		$r = array(
						'Order_ID',
						'Order_Number',
						'Order_Date',
						'Order_Changed',
						'Customer_Number',
						'Customer_Group',
						'Customer_Group_Name',
						'Customer_Gender',
						'Customer_Name',
						'Customer_Email',
						'Shipping_Name',
						'Shipping_Company',
						'Shipping_Street',
						'Shipping_Zip',
						'Shipping_City',
						'Shipping_State',
						'Shipping_State_Name',
						'Shipping_Country',
						'Shipping_Country_Name',
						'Shipping_Phone_Number',
						'Shipping_Gender',
						'Billing_Name',
						'Billing_Company',
						'Billing_Street',
						'Billing_Zip',
						'Billing_City',
						'Billing_State',
						'Billing_State_Name',
						'Billing_Country',
						'Billing_Country_Name',
						'Billing_Phone_Number',
						'Billing_Gender',
						'Customer_HashCode'
		    		);
		    	}
    			break;
    		case 'orderItems':
    			$r = array(
						'Order_ID',
						'Order_Number',
						'Order_Date',
						'Order_Changed',
						'Order_Item_Increment',
						'Item_ID',
						'Item_SKU',
						'Item_Name',
						'Item_Status',
						'Item_Options',
						'Item_Original_Price',
						'Item_Price',
						'Item_Qty_Ordered',
						'Item_Qty_Invoiced',
						'Item_Qty_Shipped',
						'Item_Qty_Canceled',
						'Item_Qty_Refunded',
						'Item_Tax',
						'Item_Discount',
						'Item_Total',
						'Item_Cost',
						'productKey', 'quoteItemId', 'isVirtual', 'freeShipping', 'isQtyDecimal', 'noDiscount', 'createdAt', 
						'updatedAt', 'qtyCanceled', 'qtyInvoiced', 'qtyOrdered', 'qtyRefunded', 'qtyShipped', 'cost', 'price', 
						'basePrice', 'originalPrice', 'baseOriginalPrice', 'taxPercent', 'taxAmount', 'baseTaxAmount', 'taxInvoiced', 
						'baseTaxInvoiced', 'discountPercent', 'discountAmount', 'baseDiscountAmount', 'discountInvoiced', 
						'baseDiscountInvoiced', 'amountRefunded', 'baseAmountRefunded', 'rowTotal', 'baseRowTotal', 'rowInvoiced', 
						'baseRowInvoiced', 'baseTaxBeforeDiscount', 'taxBeforeDiscount', 'productType', 'productOptions', 'sku', 
						'appliedRuleIds', 'giftMessageId'
		    	);
    			break;
    		case 'customers':
    			if(!$pdata) {
    				$r = array(
						'Customer_Number',
						'Customer_Group',
						'Customer_Group_Name',
						'Customer_Gender',
						'Customer_Name',
						'Customer_Email',
						'Customer_Company',
						'Customer_Street',
						'Customer_Zip',
						'Customer_City',
						'Customer_State',
						'Customer_State_Name',
						'Customer_Country',
						'Customer_Country_Name',
						'Customer_Phone_Number',
						'Costomer_HashCode'
		    		);
				} else {
		    		$r = array(
						'Customer_Number',
						'Customer_Gender',
						'Customer_Zip',
						'Customer_City',
						'Customer_State',
						'Customer_State_Name',
						'Customer_Country',
						'Customer_Country_Name',
						'Costomer_HashCode'
		    		);
		    	}    			
		    	break;
    		case 'countries':
					$r = array (
						'Country_Id',
						'Country_Name'
					);
					break;
				default:
					if($cols) {
						foreach ($cols as $col => $val) if(!in_array($col, $skipCols)) { if(array_key_exists($col, $renameCols)) $r[] = $renameCols[$col]; else $r[] = $col; }
					}
    	}
      return $r;
    }

		protected function writeCollection($entries, $fp, $group, $skipCols=array())
    {
			$data = array();
			foreach ($entries as $col => $value) {
      	if(count($skipCols)>0) {
					if(!in_array($col, $skipCols)) $data[$col] = str_replace(chr(13).chr(10),' ',str_replace('"',"'",$value));
				} else {
					$data[$col] = str_replace(chr(13).chr(10),' ',str_replace('"',"'",$value));
				}
			}
			fputcsv($fp, $data, self::DELIMITER, self::ENCLOSURE);
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
						$this->getCcType($order),
						$shippingAddress ? $shippingAddress->getData("postcode") : $billingAddress->getData("postcode"),
						$shippingAddress ? $shippingAddress->getData("city") : $billingAddress->getData("city"),
						$shippingAddress ? $shippingAddress->getCountry() : $billingAddress->getCountry(),
						md5($order->getCustomerEmail()),
						$order->getParentId(),
						$order->getIsActive(),
						$order->getStatus(),
						$order->getComment(),
						$order->getShippingAddressIncrementId(),
						$order->getShippingAddressParentId(),
						$order->getShippingAddressRegionId(),
						$order->getStoreId(),
						$order->getBillingAddressId(),
						$order->getShippingAddressAddressId(),
						$order->getQuoteId(),
						$order->getIsVirtual(),
						$order->getCustomerGroupId(),
						$order->getCustomerIsGuest(),
						$order->getShippingAddressAddressId(),
						$order->getShippingAddressCreatedAt(),
						$order->getShippingAddressUpdatedAt(),
						$this->formatPrice($order->getTaxAmount(), $order),
						$this->formatPrice($order->getShippingAmount(), $order),
						$this->formatPrice($order->getDiscountAmount(), $order),
						$this->formatPrice($order->getSubtotal(), $order),
						$this->formatPrice($order->getGrandTotal(), $order),
						$this->formatPrice($order->getTotalPaid(), $order),
						$this->formatPrice($order->getTotalRefunded(), $order),
						$this->formatPrice($order->getTotalQtyOrdered(), $order),
						$this->formatPrice($order->getTotalCanceled(), $order),
						$this->formatPrice($order->getTotalInvoiced(), $order),
						$this->formatPrice($order->getBaseTaxAmount(), $order),
						$this->formatPrice($order->getBaseShippingAmount(), $order),
						$this->formatPrice($order->getBaseDiscountAmount(), $order),
						$this->formatPrice($order->getBaseGrandTotal(), $order),
						$this->formatPrice($order->getBaseSubtotal(), $order),
						$this->formatPrice($order->getBaseTotalPaid(), $order),
						$this->formatPrice($order->getBaseTotalRefunded(), $order),
						$this->formatPrice($order->getBaseTotalQtyOrdered(), $order),
						$this->formatPrice($order->getBaseTotalCanceled(), $order),
						$this->formatPrice($order->getBaseTotalInvoiced(), $order),
						$this->formatPrice($order->getStoreToBaseRate(), $order),
						$this->formatPrice($order->getStoreToOrderRate(), $order),
						$this->formatPrice($order->getBaseToGlobalRate(), $order),
						$this->formatPrice($order->getBaseToOrderRate(), $order),
						$order->getIsActive(),
						$order->getStoreName(),
						$order->getStatus(),
						$order->getState(),
						$order->getAppliedRuleIds(),
						$order->getGlobalCurrencyCode(),
						$order->getBaseCurrencyCode(),
						$order->getStoreCurrencyCode(),
						$order->getOrderCurrencyCode(),
						$order->getShippingMethod(),
						$order->getShippingDescription(),
						$order->getGiftMessageId(),
						$order->getShippingAddressIsActive(),
						$order->getShippingAddressAddressType(),
						$order->getShippingAddressRegion(),
						$order->getPaymentMethod());
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
							$shippingAddress ? $shippingAddress->getData("gender") : '',
							$billingAddress->getData("postcode"),
							$billingAddress->getData("city"),
							$billingAddress->getRegionCode(),
							$billingAddress->getRegion(),
							$billingAddress->getCountry(),
							$billingAddress->getCountryModel()->getName(),
							$billingAddress->getData("gender"),
							md5($order->getCustomerEmail()));
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
							$shippingAddress ? $shippingAddress->getData("telephone") : '',
							$shippingAddress ? $shippingAddress->getData("gender") : '',
							$billingAddress->getName(),
							$billingAddress->getData("company"),
							$this->getStreet($billingAddress),
							$billingAddress->getData("postcode"),
							$billingAddress->getData("city"),
							$billingAddress->getRegionCode(),
							$billingAddress->getRegion(),
							$billingAddress->getCountry(),
							$billingAddress->getCountryModel()->getName(),
							$billingAddress->getData("telephone"),
							$billingAddress->getData("gender"),
							md5($order->getCustomerEmail()));
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
				if (!is_object($product)) $product = Mage::getModel('catalog/product')->loadByAttribute('sku', trim($this->getItemSku($item)));
				// $item->getProductId() liefert die Id des Basisartikels bei Variationen
        return array(
            $itemInc,
            (is_object($product) ? $product->getId() : '-1'),
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
            $this->formatPrice($this->getItemTotal($item), $order),
            $this->formatPrice($item->getBaseCost(), $order),
            $item->getProductId(),
						$item->getQuoteItemId(),
						$item->getIsVirtual(),
						$item->getFreeShipping(),
						$item->getIsQtyDecimal(),
						$item->getNoDiscount(),
						$item->getCreatedAt(),
						$item->getUpdatedAt(),
						$item->getQtyCanceled(),
						$item->getQtyInvoiced(),
						$item->getQtyOrdered(),
						$item->getQtyRefunded(),
						$item->getQtyShipped(),
						$item->getCost(),
						$item->getPrice(),
						$item->getBasePrice(),
						$item->getOriginalPrice(),
						$item->getBaseOriginalPrice(),
						$item->getTaxPercent(),
						$item->getTaxAmount(),
						$item->getBaseTaxAmount(),
						$item->getTaxInvoiced(),
						$item->getBaseTaxInvoiced(),
						$item->getDiscountPercent(),
						$item->getDiscountAmount(),
						$item->getBaseDiscountAmount(),
						$item->getDiscountInvoiced(),
						$item->getBaseDiscountInvoiced(),
						$item->getAmountRefunded(),
						$item->getBaseAmountRefunded(),
						$item->getRowTotal(),
						$item->getBaseRowTotal(),
						$item->getRowInvoiced(),
						$item->getBaseRowInvoiced(),
						$item->getBaseTaxBeforeDiscount(),
						$item->getTaxBeforeDiscount(),
						$item->getProductType(),
						serialize($item->getProductOptions()),
						$item->getSku(),
						$item->getAppliedRuleIds(),
						$item->getGiftMessageId()
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