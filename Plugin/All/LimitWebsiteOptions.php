<?php

namespace Dulv\SeparateAdminUser\Plugin\All;

class LimitWebsiteOptions
{
    protected $_helper;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper
    ){
        $this->_helper = $_helper;
    }

    public function afterToOptionArray(
        \Magento\Config\Model\Config\Source\Website\OptionHash $subject,
        $result
    ){
        if($this->_helper->isRootAdmin())
        {
            return $result;
        }
        return $this->_helper->getWebsiteOptions();
    }
}
