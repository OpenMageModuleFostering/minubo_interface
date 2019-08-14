<?php
class Minubo_Interface_Block_Adminhtml_System_Config_Form_Button extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /*
     * Set template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('minubo/system/config/button.phtml');
    }

    /**
     * Return element html
     *
     * @param  Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        // $html = $this->_toHtml();

        $this->setElement($element);
        $url = $this->getUrl('minuboface/export/newHash');

        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('scalable')
                    ->setLabel('Generate')
                    ->setOnClick("javscript:function rs(l, c) { l = l || 30; c = c || '0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789'; var r = ''; for (var i = 0; i < l; i++) { var p = Math.floor(Math.random() * c.length);	r += c.substring(p,p+1); } return r; } document.getElementById('minubo_interface_settings_hash').value=rs()")
                    ->toHtml();


        return $html;
    }

    /**
     * Return ajax url for button
     *
     * @return string
     */
    public function getAjaxGetHash()
    {
        // return 'bxZ2vbChmellAibG2w1uWMmE87R65G';
        // return Mage::helper('adminhtml')->getUrl('minuboface/export/newHash');
        return 'test';
    }

    /**
     * Generate button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
            'id'        => 'minubo_hash_button',
            'label'     => $this->helper('adminhtml')->__('Generate'),
            'onclick'   => 'javascript:getHash(); return false;'
        ));

        return $button->toHtml();
    }
}