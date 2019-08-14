<?php
class Minubo_Interface_Model_Mysql4_Orders extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/orders', 'entity_id');
    }

    protected function getColumns() {
    		$r = array('o.entity_id as orderKey','increment_id as orderNumber','created_At','updated_At',"ifnull(oab.customer_address_id,concat('g_',oab.entity_id)) as customerKey",
					'ifnull(oas.postcode,oab.postcode) as shippingAddressPostcode','ifnull(oas.city,oab.city) as shippingAddressCity','ifnull(oas.country_id,oab.country_id) as shippingAddressCountryId',
          'ifnull(oas.entity_id,oab.entity_id) as shippingAddressIncrementId','ifnull(oas.parent_id,oab.parent_id) as shippingAddressParentId',
					'ifnull(oas.region_id,oab.region_id) as shippingAddressRegionId','store_Id','billing_Address_Id','shipping_Address_Id','quote_Id','is_Virtual','customer_Group_Id','customer_Is_Guest',
					'shipping_address_id as shippingAddressAddressId',
					'o.created_at as shippingAddressCreatedAt','o.updated_at as shippingAddressUpdatedAt','tax_Amount','shipping_Amount','discount_Amount','subtotal','grand_Total','total_Paid',
					'total_Refunded','total_Qty_Ordered','total_Canceled','total_Invoiced',
					'base_Tax_Amount','base_Shipping_Amount','base_Discount_Amount','base_Grand_Total','base_Subtotal','base_Total_Paid','base_Total_Refunded',
					'base_Total_Qty_Ordered','base_Total_Canceled','base_Total_Invoiced',
					'store_To_Base_Rate','store_To_Order_Rate','base_To_Global_Rate','base_To_Order_Rate', /* is_active */ 'store_Name','status','state','applied_Rule_Ids',
					'global_Currency_Code','base_Currency_Code','store_Currency_Code',
					'order_Currency_Code','shipping_Method','shipping_Description', /* shippingAddressIsActive */ 'ifnull(oas.address_type,oab.address_type) as shippingAddressAddressType',
					'ifnull(oas.region,oab.region) as shippingAddressRegion','op.method as paymentMethod','oab.customer_address_id','md5(o.customer_email) as Customer_HashCode');

				$fields = Mage::getStoreConfig('minubo_interface/settings/orderfields',Mage::app()->getStore());
        $f = explode(',', str_replace(' ','',$fields));
        return array_merge($r, $f);
    }

		/*
		SELECT o.entity_id as orderKey,o.increment_id as orderNumber,o.created_At,o.updated_At, ifnull(oab.customer_address_id,concat('g_',oab.entity_id)) as customerKey,
					ifnull(oas.postcode,oab.postcode) as shippingAddressPostcode,ifnull(oas.city,oab.city) as shippingAddressCity,ifnull(oas.country_id,oab.country_id) as shippingAddressCountryId,
          ifnull(oas.entity_id,oab.entity_id) as shippingAddressIncrementId,ifnull(oas.parent_id,oab.parent_id) as shippingAddressParentId,
					ifnull(oas.region_id,oab.region_id) as shippingAddressRegionId,o.store_Id,o.billing_Address_Id,o.shipping_Address_Id,o.quote_Id,o.is_Virtual,o.customer_Group_Id,o.customer_Is_Guest,
					shipping_address_id as shippingAddressAddressId,
					o.created_at as shippingAddressCreatedAt,o.updated_at as shippingAddressUpdatedAt,o.tax_Amount,o.shipping_Amount,o.discount_Amount,o.subtotal,o.grand_Total,o.total_Paid,
					o.total_Refunded,o.total_Qty_Ordered,o.total_Canceled,o.total_Invoiced,
					o.base_Tax_Amount,o.base_Shipping_Amount,o.base_Discount_Amount,o.base_Grand_Total,o.base_Subtotal,o.base_Total_Paid,o.base_Total_Refunded,
					o.base_Total_Qty_Ordered,o.base_Total_Canceled,o.base_Total_Invoiced,
					o.store_To_Base_Rate,o.store_To_Order_Rate,o.base_To_Global_Rate,o.base_To_Order_Rate,o.store_Name,o.status,o.state,o.applied_Rule_Ids,
					o.global_Currency_Code,o.base_Currency_Code,o.store_Currency_Code,
					o.order_Currency_Code,o.shipping_Method,o.shipping_Description, ifnull(oas.address_type,oab.address_type) as shippingAddressAddressType,
					ifnull(oas.region,oab.region) as shippingAddressRegion,op.method as paymentMethod,oab.customer_address_id,md5(o.customer_email) as Customer_HashCode
		FROM `sales_flat_order` o
		left outer join sales_flat_order_address oas on o.shipping_address_id = oas.entity_id
		left outer join sales_flat_order_payment op on o.entity_id = op.parent_id
		left outer join sales_flat_order_address oab on o.billing_address_id = oab.entity_id
		where o.store_id = 1
		*/

    public function loadByField($field,$value){
        $table = $this->getMainTable();
        $table2 = $this->getTable('sales_flat_order_address');
        $cond2 = $this->_getReadAdapter()->quoteInto('o.shipping_address_id = oas.entity_id ','');
        $table3 = $this->getTable('sales_flat_order_payment');
        $cond3 = $this->_getReadAdapter()->quoteInto('o.entity_id = op.parent_id ','');
        $table4 = $this->getTable('sales_flat_order_address');
        $cond4 = $this->_getReadAdapter()->quoteInto("o.billing_address_id = oab.entity_id ",'');
        $where = $this->_getReadAdapter()->quoteInto("$field = ?", $value);
        $select = $this->_getReadAdapter()->select()->from(array('o'=>$table))
                                                    ->joinLeft(array('oas'=>$table2), $cond2)
                                                    ->joinLeft(array('op'=>$table3), $cond3)
                                                    ->joinLeft(array('oab'=>$table4), $cond4)
                                                    ->reset('columns')
                                                    ->columns($this->getColumns())
                                                    ->where($where);
        $id = $this->_getReadAdapter()->fetchOne($select);
        return $id;
    }

    public function loadAllByStoreId($store_id){
        $table = $this->getMainTable();
        $table2 = $this->getTable('sales_flat_order_address');
        $cond2 = $this->_getReadAdapter()->quoteInto('o.shipping_address_id = oas.entity_id ','');
        $table3 = $this->getTable('sales_flat_order_payment');
        $cond3 = $this->_getReadAdapter()->quoteInto('o.entity_id = op.parent_id ','');
        $table4 = $this->getTable('sales_flat_order_address');
        $cond4 = $this->_getReadAdapter()->quoteInto("o.billing_address_id = oab.entity_id ",'');
        $where = $this->_getReadAdapter()->quoteInto("o.store_id = ?", $store_id);
       $select = $this->_getReadAdapter()->select()->from(array('o'=>$table))
                                        ->joinLeft(array('oas'=>$table2), $cond2)
                                        ->joinLeft(array('op'=>$table3), $cond3)
                                        ->joinLeft(array('oab'=>$table4), $cond4)
                                        ->reset('columns')
                                        ->columns($this->getColumns())
                                        ->where($where)
                                        ->order('o.entity_id');
        return $this->_getReadAdapter()->fetchAll($select);
    }

    public function loadLimitedByStoreId($limit, $offset, $store_id){
        $table = $this->getMainTable();
        $table2 = $this->getTable('sales_flat_order_address');
        $cond2 = $this->_getReadAdapter()->quoteInto('o.shipping_address_id = oas.entity_id ','');
        $table3 = $this->getTable('sales_flat_order_payment');
        $cond3 = $this->_getReadAdapter()->quoteInto('o.entity_id = op.parent_id ','');
        $table4 = $this->getTable('sales_flat_order_address');
        $cond4 = $this->_getReadAdapter()->quoteInto("o.billing_address_id = oab.entity_id ",'');
        $where = $this->_getReadAdapter()->quoteInto("o.store_id = ?", $store_id);
        $select = $this->_getReadAdapter()->select()
                                        ->from(array('o'=>$table))
                                        ->joinLeft(array('oas'=>$table2), $cond2)
                                        ->joinLeft(array('op'=>$table3), $cond3)
                                        ->joinLeft(array('oab'=>$table4), $cond4)
                                        ->reset('columns')
                                        ->columns($this->getColumns())
                                        ->where($where)
                                        ->limit($limit, $offset)
                                        ->order('o.entity_id');
        return $this->_getReadAdapter()->fetchAll($select);
    }

}
?>