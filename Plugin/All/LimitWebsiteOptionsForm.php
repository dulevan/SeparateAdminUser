<?php

namespace Dulv\SeparateAdminUser\Plugin\All;

class LimitWebsiteOptionsForm
{
    protected $_helper;
    protected $_options;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Dulv\SeparateAdminUser\Helper\Options $_options
    ){
        $this->_helper = $_helper;
        $this->_options = $_options;
    }
    public function afterGetWebsiteValuesForForm(\Magento\Store\Model\System\Store $subject, $result){
        if($this->_helper->isRootAdmin()){
            return $result;
        }
        return $this->_options->getWebsiteOption();
    }

    public function afterGetStoreValuesForForm(\Magento\Store\Model\System\Store $subject, $result){
        if($this->_helper->isRootAdmin()){
            return $result;
        }
        return $this->_options->getStoreOptionsForMultipleSelect();
    }
/*
    public function afterGetStoresStructure(\Magento\Store\Model\System\Store $subject, $result){
        if($this->_helper->isRootAdmin())
        {
            return $result;
        }
        $result = $this->_options->getStoresStruct();
        $this->_helper->logCheck($result);

        return $this->_options->getStoresStruct();
    }
*/
}
