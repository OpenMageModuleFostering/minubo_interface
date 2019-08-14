<?php
class Minubo_Interface_Model_Mysql4_Products extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/products', 'entity_id');
    }
    
    protected function getColumns() {
        $r = array('entity_id','sku','name','type_id','attribute_set_id','created_at','type_id as typeKey','weight',
            'visibility','has_options','gift_message_available','price','special_price',
            'special_from_date','special_to_date','tax_class_id','required_options',
            'price_type','sku_type','shipment_type','cost','msrp_enabled','msrp',
            'small_image','thumbnail','news_from_date','news_to_date','url_key','url_path',
            'is_recurring','recurring_profile','image_label','small_image_label',
            'thumbnail_label','updated_at','weight_type','price_view',
            'links_purchased_separately','links_exist',
            'msrp_display_actual_price_type','short_description');
        $field1 = Mage::getStoreConfig('minubo_interface/settings/productbrand',Mage::app()->getStore());
        if(!$field1) $field1 = 'created_at as dummy_brand';
        $field2 = Mage::getStoreConfig('minubo_interface/settings/productorigin',Mage::app()->getStore());
        if(!$field2) $field2 = 'created_at as dummy_origin';
        $fields = Mage::getStoreConfig('minubo_interface/settings/productfields',Mage::app()->getStore());
        $f = explode(',', str_replace(' ','',$fields));
        return array_merge($r, array($field1, $field2), $f);
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

    public function loadAllByStoreId($store_id=''){
        $table = str_replace('_1','_'.$store_id,$this->getMainTable());
        $where = $this->_getReadAdapter()->quoteInto("entity_id > ?", 0);
        $select = $this->_getReadAdapter()->select()->from($table)
                                        ->reset('columns')
                                        ->columns($this->getColumns())
                                        ->where($where)
                                        ->order('entity_id');
        return $this->_getReadAdapter()->fetchAll($select);
    }

    public function loadLimitedByStoreId($limit, $offset, $store_id=''){
        $table = str_replace('_1','_'.$store_id,$this->getMainTable());
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