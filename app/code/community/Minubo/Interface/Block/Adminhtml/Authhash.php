<?php

class Minubo_Interface_Block_Adminhtml_Authhash extends Mage_Adminhtml_Block_System_Config_Form_Field {

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
        return $this->_toHtml();
    }
 
    /**
     * Return ajax url for button
     *
     * @return string
     */
    public function getAjaxCheckUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/adminhtml_atwixtweaks/check');
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
            	'id'        => 'atwixtweaks_button',
            	'label'     => $this->helper('adminhtml')->__('Check'),
            	'onclick'   => 'javascript:check(); return false;'
        	)
        );
 
        return $button->toHtml();
    }
    
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('customconfig/system/config/custombutton.phtml');
        }
        return $this;
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $originalData = $element->getOriginalData();
        $this->addData(array(
            'button_label' => Mage::helper('customconfig')->__($originalData['button_label']),
            'button_url'   => $originalData['button_url'],
            'html_id' => $element->getHtmlId(),
        ));
        return $this->_toHtml();
    }
}
