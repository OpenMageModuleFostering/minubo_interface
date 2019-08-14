<?php
class Minubo_Interface_Model_Mysql4_Invoices extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/invoices', 'entity_id');
    }

    protected function getColumns() {
    		return array('entity_id','store_id','base_grand_total','shipping_tax_amount','tax_amount','base_tax_amount','store_to_order_rate','base_shipping_tax_amount','base_discount_amount','base_to_order_rate','grand_total','shipping_amount','subtotal_incl_tax','base_subtotal_incl_tax','store_to_base_rate','base_shipping_amount','total_qty','base_to_global_rate','subtotal','base_subtotal','discount_amount','billing_address_id','is_used_for_refund','order_id','email_sent','can_void_flag','state','shipping_address_id','cybersource_token','store_currency_code','transaction_id','order_currency_code','base_currency_code','global_currency_code','increment_id','created_at','updated_at','hidden_tax_amount','base_hidden_tax_amount','shipping_hidden_tax_amount','base_shipping_hidden_tax_amnt','shipping_incl_tax','base_shipping_incl_tax');
    }

    public function loadByField($field,$value){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("$field = ?", $value);
        $select = $this->_getReadAdapter()->select()->from($table)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where);
        $id = $this->_getReadAdapter()->fetchOne($sql);
        return $id;
    }

    public function loadAll(){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("entity_id > ?", 0);
				$select = $this->_getReadAdapter()->select()->from($table)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where)
        																						->order('entity_id');
				return $this->_getReadAdapter()->fetchAll($select);
    }

    public function loadLimited($limit, $offset){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("entity_id > ?", 0);
				$select = $this->_getReadAdapter()->select()->from($table)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where)
        																						->limit($limit, $offset)
        																						->order('entity_id');
				return $this->_getReadAdapter()->fetchAll($select);
    }

}
?>