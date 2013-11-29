<?php
/*SearchPlus*/
class Gdav_SearchPlus_Adminhtml_Model_System_Config_Source_Sortorder
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'desc', 'label'=>Mage::helper('searchplus')->__('Descending')),
            array('value'=>'asc', 'label'=>Mage::helper('searchplus')->__('Ascending'))
        );
    }
}