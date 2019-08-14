<?php
class Minubo_Interface_Model_Mysql4_Regions extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/regions', 'region_id');
    }

    public function loadByField($field,$value){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("$field = ?", $value);
        $select = $this->_getReadAdapter()->select()->from($table,array('entity_id','parent_id','position','name'))->where($where);
        $id = $this->_getReadAdapter()->fetchOne($sql);
        return $id;
    }

    public function loadAll(){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("region_id > ?", 0);
				// $select = $this->_getReadAdapter()->select()->from($table)->columns(array('region_id','country_id','code','default_name'))->where($where)->limit(10,5)->order('created_at');
				$select = $this->_getReadAdapter()->select()->from($table,array('region_id','country_id','code','default_name'))->where($where)->order('region_id');
				return $this->_getReadAdapter()->fetchAll($select);
    }

}
?>