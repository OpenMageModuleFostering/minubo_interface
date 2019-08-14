<?php
class Minubo_Interface_Model_Mysql4_Categories extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/categories', 'entity_id');
    }

    public function loadByField($field,$value){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("$field = ?", $value);
        $select = $this->_getReadAdapter()->select()->from($table,array('entity_id','parent_id','position','name','level','image','url_key','url_path'))->where($where);
        $id = $this->_getReadAdapter()->fetchOne($sql);
        return $id;
    }

    public function loadAll(){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("entity_id > ?", 0);
		// $select = $this->_getReadAdapter()->select()->from($table)->columns(array('entity_id','parent_id','name'))->where($where)->limit(10,5)->order('created_at');
		$select = $this->_getReadAdapter()->select()->from($table,array('entity_id','parent_id','position','name','level','image','url_key','url_path'))->where($where)->order('created_at');
		return $this->_getReadAdapter()->fetchAll($select);
    }

	public function loadLimited($limit, $offset){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("entity_id > ?", 0);
		$select = $this->_getReadAdapter()->select()->from($table,array('entity_id','parent_id','position','name','level','image','url_key','url_path'))->where($where)->limit($limit, $offset)->order('created_at');
		return $this->_getReadAdapter()->fetchAll($select);
    }
}
?>