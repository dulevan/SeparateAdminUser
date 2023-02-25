<?php

namespace Dulv\SeparateAdminUser\Plugin\Store;

class LimitOptions
{
    protected $_optionsHelper;
    protected $_helper;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Options $options,
        \Dulv\SeparateAdminUser\Helper\Data $_helper
    ){
        $this->_optionsHelper = $options;
        $this->_helper = $_helper;
    }
    public function afterToOptionArray(\Magento\Store\Ui\Component\Listing\Column\Store\Options $subject, $result)
    {
        if($this->_helper->isRootAdmin()){
            return $result;
        }
        return $this->_optionsHelper->getStoreOptions();
    }
}
