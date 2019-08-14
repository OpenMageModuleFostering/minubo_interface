<?php
class Minubo_Interface_Model_Mysql4_Orders extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/orders', 'entity_id');
    }

    protected function getColumns() {
    		return array('o.entity_id as orderKey','increment_id as orderNumber','created_At','updated_At',"ifnull(oab.customer_address_id,concat('g_',oab.entity_id)) as customerKey",
					// 'o.customer_id as customerKey','o.coupon_code as Credit_Card_Type',
                    'oas.postcode as shippingAddressPostcode','oas.city as shippingAddressCity','oas.country_id as shippingAddressCountryId',
                    // 'o.coupon_code as parentKey','o.coupon_code as isActive','o.status','o.coupon_code as comment',
					'oas.entity_id as shippingAddressIncrementId','oas.parent_id as shippingAddressParentId',
					'oas.region_id as shippingAddressRegionId','store_Id','billing_Address_Id','shipping_Address_Id','quote_Id','is_Virtual','customer_Group_Id','customer_Is_Guest',
					'shipping_address_id as shippingAddressAddressId',
					'o.created_at as shippingAddressCreatedAt','o.updated_at as shippingAddressUpdatedAt','tax_Amount','shipping_Amount','discount_Amount','subtotal','grand_Total','total_Paid',
					'total_Refunded','total_Qty_Ordered','total_Canceled','total_Invoiced',
					'base_Tax_Amount','base_Shipping_Amount','base_Discount_Amount','base_Grand_Total','base_Subtotal','base_Total_Paid','base_Total_Refunded',
					'base_Total_Qty_Ordered','base_Total_Canceled','base_Total_Invoiced',
					'store_To_Base_Rate','store_To_Order_Rate','base_To_Global_Rate','base_To_Order_Rate', /* is_active */ 'store_Name','status','state','applied_Rule_Ids',
					'global_Currency_Code','base_Currency_Code','store_Currency_Code',
					'order_Currency_Code','shipping_Method','shipping_Description', /* shippingAddressIsActive */ 'oas.address_type as shippingAddressAddressType',
					'oas.region as shippingAddressRegion','op.method as paymentMethod','oab.customer_address_id','md5(o.customer_email) as Customer_HashCode');
    }

		/*
		SELECT o.entity_id as orderKey, increment_id as orderNumber, created_At, updated_At, o.customer_id as customerKey,
					oas.postcode as shippingAddressPostcode, oas.city as shippingAddressCity, oas.country_id as shippingAddressCountryId, 
					oas.entity_id as shippingAddressIncrementId, oas.parent_id as shippingAddressParentId, 
					oas.region_id as shippingAddressRegionId, store_Id, 
					billing_Address_Id, shipping_Address_Id, quote_Id, is_Virtual, customer_Group_Id, customer_Is_Guest, 
					shipping_address_id as shippingAddressAddressId, 
					o.created_at as shippingAddressCreatedAt, o.updated_at as shippingAddressUpdatedAt,
					tax_Amount, shipping_Amount, discount_Amount, subtotal, grand_Total, total_Paid, 
					total_Refunded, total_Qty_Ordered, total_Canceled, total_Invoiced, 
					base_Tax_Amount, base_Shipping_Amount, base_Discount_Amount, base_Grand_Total, base_Subtotal, base_Total_Paid, base_Total_Refunded, 
					base_Total_Qty_Ordered, base_Total_Canceled, base_Total_Invoiced, 
					store_To_Base_Rate, store_To_Order_Rate, base_To_Global_Rate, base_To_Order_Rate, store_Name, status, state, applied_Rule_Ids, 
					global_Currency_Code, base_Currency_Code, store_Currency_Code, 
					order_Currency_Code, shipping_Method, shipping_Description, oas.gift_Message_Id
		FROM `sales_flat_order` o
		inner join sales_flat_order_address oas on o.shipping_address_id = oas.entity_id 
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
                                                    ->join(array('oas'=>$table2), $cond2)
                                                    ->join(array('op'=>$table3), $cond3)
                                                    ->join(array('oab'=>$table4), $cond4)
                                                    ->reset('columns')
                                                    ->columns($this->getColumns())
                                                    ->where($where);
        $id = $this->_getReadAdapter()->fetchOne($select);
        return $id;
    }

    public function loadAll(){
        $table = $this->getMainTable();
        $table2 = $this->getTable('sales_flat_order_address');
        $cond2 = $this->_getReadAdapter()->quoteInto('o.shipping_address_id = oas.entity_id ','');
        $table3 = $this->getTable('sales_flat_order_payment');
        $cond3 = $this->_getReadAdapter()->quoteInto('o.entity_id = op.parent_id ','');
        $table4 = $this->getTable('sales_flat_order_address');
        $cond4 = $this->_getReadAdapter()->quoteInto("o.billing_address_id = oab.entity_id ",'');
        $where = $this->_getReadAdapter()->quoteInto("o.entity_id > ?", 0);
        $select = $this->_getReadAdapter()->select()->from(array('o'=>$table))
                                        ->join(array('oas'=>$table2), $cond2)
                                        ->join(array('op'=>$table3), $cond3)
                                        ->join(array('oab'=>$table4), $cond4)
                                        ->reset('columns')
                                        ->columns($this->getColumns())
                                        ->where($where)
                                        ->order('o.entity_id');
        return $this->_getReadAdapter()->fetchAll($select);
    }

    public function loadLimited($limit, $offset){
        $table = $this->getMainTable();
        $table2 = $this->getTable('sales_flat_order_address');
        $cond2 = $this->_getReadAdapter()->quoteInto('o.shipping_address_id = oas.entity_id ','');
        $table3 = $this->getTable('sales_flat_order_payment');
        $cond3 = $this->_getReadAdapter()->quoteInto('o.entity_id = op.parent_id ','');
        $table4 = $this->getTable('sales_flat_order_address');
        $cond4 = $this->_getReadAdapter()->quoteInto("o.billing_address_id = oab.entity_id ",'');
        $where = $this->_getReadAdapter()->quoteInto("o.entity_id > ?", 0);
        $select = $this->_getReadAdapter()->select()
                                        ->from(array('o'=>$table))
                                        ->join(array('oas'=>$table2), $cond2)
                                        ->join(array('op'=>$table3), $cond3)
                                        ->join(array('oab'=>$table4), $cond4)
                                        ->reset('columns')
                                        ->columns($this->getColumns())
                                        ->where($where)
                                        ->limit($limit, $offset)
                                        ->order('o.entity_id');
        return $this->_getReadAdapter()->fetchAll($select);
    }

}
?>