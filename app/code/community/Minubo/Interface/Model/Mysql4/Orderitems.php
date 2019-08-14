<?php
class Minubo_Interface_Model_Mysql4_Orderitems extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/orderitems', 'item_id');
    }

    protected function getColumns() {
    		return array('op.order_id as orderKey','op.item_id as orderLineKey','oi.product_id as productKey',
    							'op.quote_Item_Id','op.is_Virtual','op.free_Shipping',
    							'op.is_Qty_Decimal','op.no_Discount','op.created_At','op.updated_At','op.qty_Canceled','op.qty_Invoiced','op.qty_Ordered',
    							'op.qty_Refunded','op.qty_Shipped','op.base_cost as cost','op.price','op.base_Price','op.original_Price','op.base_Original_Price',
    							'op.tax_Percent','op.tax_Amount','op.base_Tax_Amount','op.tax_Invoiced','op.base_Tax_Invoiced','op.discount_Percent',
    							'op.discount_Amount','op.base_Discount_Amount','op.discount_Invoiced','op.base_Discount_Invoiced','op.amount_Refunded',
    							'op.base_Amount_Refunded','op.row_Total','op.base_Row_Total','op.row_Invoiced','op.base_Row_Invoiced','op.base_Tax_Before_Discount',
    							'op.tax_Before_Discount','op.product_Type','op.product_Options','op.sku','op.applied_Rule_Ids','op.gift_Message_Id','o.store_id');
    }

		public function loadByField($field,$value){
        $table = $this->getMainTable();
        $tableHeader = $this->getTable('sales_flat_order');
        $condHeader = $this->_getReadAdapter()->quoteInto('op.order_id = o.entity_id','');
        $table2 = $this->getTable('sales_flat_order_item');
        $cond2 = $this->_getReadAdapter()->quoteInto('op.item_id = IFNULL(oi.parent_item_id,oi.item_id)','');
        $where = $this->_getReadAdapter()->quoteInto("$field = ? AND oi.product_type = 'simple' AND o.store_id = ?", $value, $store_id);
        $select = $this->_getReadAdapter()->select()->from(array('op'=>$table))
                                                    ->join(array('oi'=>$table2), $cond2)
                                        						->join(array('o'=>$tableHeader), $condHeader)
                                                    ->reset('columns')
                                                    ->columns($this->getColumns())
                                                    ->where($where);
        $id = $this->_getReadAdapter()->fetchOne($select);
        return $id;
    }

    public function loadAllByStoreId($store_id){
        $table = $this->getMainTable();
        $tableHeader = $this->getTable('sales_flat_order');
        $condHeader = $this->_getReadAdapter()->quoteInto('op.order_id = o.entity_id','');
        $table2 = $this->getTable('sales_flat_order_item');
        $cond2 = $this->_getReadAdapter()->quoteInto('op.item_id = IFNULL(oi.parent_item_id,oi.item_id)','');
        $where = $this->_getReadAdapter()->quoteInto("oi.product_type='simple' AND o.store_id = ?", $store_id);
        $select = $this->_getReadAdapter()->select()
                                        ->from(array('op'=>$table))
                                        ->join(array('oi'=>$table2), $cond2)
                                        ->join(array('o'=>$tableHeader), $condHeader)
                                        ->reset('columns')
                                        ->columns($this->getColumns())
                                        ->where($where)
                                        ->order('op.item_id');
        return $this->_getReadAdapter()->fetchAll($select);
    }

    public function loadLimitedByStoreId($limit, $offset, $store_id){
        $table = $this->getMainTable();
        $tableHeader = $this->getTable('sales_flat_order');
        $condHeader = $this->_getReadAdapter()->quoteInto('op.order_id = o.entity_id','');
        $table2 = $this->getTable('sales_flat_order_item');
        $cond2 = $this->_getReadAdapter()->quoteInto('op.item_id = IFNULL(oi.parent_item_id,oi.item_id)','');
        $where = $this->_getReadAdapter()->quoteInto("oi.product_type='simple' AND o.store_id = ?", $store_id);
        $select = $this->_getReadAdapter()->select()
                                        ->from(array('op'=>$table))
                                        ->join(array('oi'=>$table2), $cond2)
                                        ->join(array('o'=>$tableHeader), $condHeader)
                                        ->reset('columns')
                                        ->columns($this->getColumns())
                                        ->where($where)
                                        ->limit($limit, $offset)
                                        ->order('op.item_id');
        return $this->_getReadAdapter()->fetchAll($select);
    }

}
?>