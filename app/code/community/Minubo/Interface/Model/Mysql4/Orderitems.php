<?php
class Minubo_Interface_Model_Mysql4_Orderitems extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/orderitems', 'item_id');
    }

    protected function getColumns() {
    		return array('o.order_id as orderKey','o.item_id as orderLineKey','oi.product_id as productKey',
    							'o.quote_Item_Id','o.is_Virtual','o.free_Shipping',
    							'o.is_Qty_Decimal','o.no_Discount','o.created_At','o.updated_At','o.qty_Canceled','o.qty_Invoiced','o.qty_Ordered',
    							'o.qty_Refunded','o.qty_Shipped','o.base_cost as cost','o.price','o.base_Price','o.original_Price','o.base_Original_Price',
    							'o.tax_Percent','o.tax_Amount','o.base_Tax_Amount','o.tax_Invoiced','o.base_Tax_Invoiced','o.discount_Percent',
    							'o.discount_Amount','o.base_Discount_Amount','o.discount_Invoiced','o.base_Discount_Invoiced','o.amount_Refunded',
    							'o.base_Amount_Refunded','o.row_Total','o.base_Row_Total','o.row_Invoiced','o.base_Row_Invoiced','o.base_Tax_Before_Discount',
    							'o.tax_Before_Discount','o.product_Type','o.product_Options','o.sku','o.applied_Rule_Ids','o.gift_Message_Id');
    }

		public function loadByField($field,$value){
        $table = $this->getMainTable();
        $table2 = $this->getTable('sales_flat_order_item');
        $cond2 = $this->_getReadAdapter()->quoteInto('o.item_id = IFNULL(oi.parent_item_id,oi.item_id)','');
        $where = $this->_getReadAdapter()->quoteInto("$field = ? AND oi.product_type = 'simple'", $value);
        $select = $this->_getReadAdapter()->select()->from(array('o'=>$table))
                                                    ->join(array('oi'=>$table2), $cond2)
                                                    ->reset('columns')
                                                    ->columns($this->getColumns())
                                                    ->where($where);
        $id = $this->_getReadAdapter()->fetchOne($select);
        return $id;
    }

    public function loadAll(){
        $table = $this->getMainTable();
        $table2 = $this->getTable('sales_flat_order_item');
        $cond2 = $this->_getReadAdapter()->quoteInto('o.item_id = IFNULL(oi.parent_item_id,oi.item_id)','');
        $where = $this->_getReadAdapter()->quoteInto("oi.product_type='simple'", '');
        $select = $this->_getReadAdapter()->select()
                                        ->from(array('o'=>$table))
                                        ->join(array('oi'=>$table2), $cond2)
                                        ->reset('columns')
                                        ->columns($this->getColumns())
                                        ->where($where)
                                        ->order('o.item_id');
        return $this->_getReadAdapter()->fetchAll($select);
    }

    public function loadLimited($limit, $offset){
        $table = $this->getMainTable();
        $table2 = $this->getTable('sales_flat_order_item');
        $cond2 = $this->_getReadAdapter()->quoteInto('o.item_id = IFNULL(oi.parent_item_id,oi.item_id)','');
        $where = $this->_getReadAdapter()->quoteInto("oi.product_type='simple'", '');
        $select = $this->_getReadAdapter()->select()
                                        ->from(array('o'=>$table))
                                        ->join(array('oi'=>$table2), $cond2)
                                        ->reset('columns')
                                        ->columns($this->getColumns())
                                        ->where($where)
                                        ->limit($limit, $offset)
                                        ->order('o.item_id');
        return $this->_getReadAdapter()->fetchAll($select);
    }

}
?>