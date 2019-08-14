<?php
class Minubo_Interface_Model_Mysql4_Creditmemos extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/creditmemos', 'entity_id');
    }

    protected function getColumns() {
    		return array('entity_id','store_id','adjustment_positive','base_shipping_tax_amount','store_to_order_rate','base_discount_amount',
                        'base_to_order_rate','grand_total','base_adjustment_negative','base_subtotal_incl_tax','shipping_amount','subtotal_incl_tax',
                        'adjustment_negative','base_shipping_amount','store_to_base_rate','base_to_global_rate','base_adjustment','base_subtotal',
                        'discount_amount','subtotal','adjustment','base_grand_total','base_adjustment_positive','base_tax_amount','shipping_tax_amount',
                        'tax_amount','order_id','email_sent','creditmemo_status','state','shipping_address_id','billing_address_id','invoice_id',
                        'store_currency_code','order_currency_code','base_currency_code','global_currency_code','transaction_id','increment_id',
                        'created_at','updated_at','hidden_tax_amount','base_hidden_tax_amount','shipping_hidden_tax_amount',
                        'base_shipping_hidden_tax_amnt','shipping_incl_tax','base_shipping_incl_tax');
    }

    public function loadByField($field,$value){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("$field = ?", $value);
        $select = $this->_getReadAdapter()->select()->from($table)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where);
        $id = $this->_getReadAdapter()->fetchOne($select);
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