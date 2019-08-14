<?php
class Minubo_Interface_Model_Mysql4_Products extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/products2', 'entity_id');
    }

    public function loadByField($field,$value){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("$field = ?", $value);
        // $select = $this->_getReadAdapter()->select()->from($table,array('entity_id','name','sku','sku_type','computer_manufacturers','computer_manufacturers_value','country_orgin','color','color_value','price','tax_class_id','cost','msrp','msrp_display_actual_price_type','msrp_enabled','shoe_size','shoe_size_value'))->where($where);
        $select = $this->_getReadAdapter()->select()->from($table)->where($where);
        $id = $this->_getReadAdapter()->fetchOne($sql);
        return $id;
    }

    public function loadAll(){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("entity_id > ?", 0);
				// $select = $this->_getReadAdapter()->select()->from($table)->columns(array('entity_id','name','sku','sku_type','computer_manufacturers','computer_manufacturers_value','country_orgin','color','color_value','price','tax_class_id','cost','msrp','msrp_display_actual_price_type','msrp_enabled','shoe_size','shoe_size_value'))->where($where)->limit(10,5)->order('created_at');
				$select = $this->_getReadAdapter()->select()->from($table)->where($where)->order('entity_id');
				return $this->_getReadAdapter()->fetchAll($select);
    }

    public function loadFiltered($lastChangeDate, $lastOrderID, $maxOrderID, $limit){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("entity_id > ?", 0);
				// $select = $this->_getReadAdapter()->select()->from($table)->columns(array('entity_id','name','sku','sku_type','computer_manufacturers','computer_manufacturers_value','country_orgin','color','color_value','price','tax_class_id','cost','msrp','msrp_display_actual_price_type','msrp_enabled','shoe_size','shoe_size_value'))->where($where)->limit(10,5)->order('created_at');
				$select = $this->_getReadAdapter()->select()->from($table)->where($where)->order('entity_id');
				return $this->_getReadAdapter()->fetchAll($select);
    }

}
?>