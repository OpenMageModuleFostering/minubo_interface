<?php
class Minubo_Interface_Model_Mysql4_Products2 extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/products2', 'entity_id');
    }

    protected function getColumns() {
        return array('entity_id','attribute_set_id','type_id','color','name','short_description','sku','price','special_price','special_from_date','special_to_date','cost','weight','small_image','thumbnail','news_from_date','news_to_date','tax_class_id','url_key','url_path','is_recurring','recurring_profile','visibility','required_options','has_options','image_label','small_image_label','thumbnail_label','created_at','updated_at','enable_googlecheckout','price_type','sku_type','weight_type','price_view','shipment_type','links_purchased_separately','links_title','manufacturer','manufacturer_value','links_exist','custom_design_from','custom_design_to','custom_layout_update','gallery','gift_message_available','image','media_gallery','meta_description','meta_keyword','meta_title','minimal_price','msrp','msrp_display_actual_price_type','msrp_enabled','old_id');
    }

    public function loadByField($field,$value){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("$field = ?", $value);
        $select = $this->_getReadAdapter()->select()
            ->from($table)
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