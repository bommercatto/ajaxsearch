<?php
/*SearchPlus*/
class Gdav_SearchPlus_Adminhtml_Model_System_Config_Source_Sortby
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'name', 'label'=>Mage::helper('searchplus')->__('Product name')),
            array('value'=>'price', 'label'=>Mage::helper('searchplus')->__('Product base price')),
			array('value'=>'sku', 'label'=>Mage::helper('searchplus')->__('Product SKU'))
        );
    }
}