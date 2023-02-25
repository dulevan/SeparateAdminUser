<?php

namespace Dulv\SeparateAdminUser\Plugin\CatalogRule;

class LimitWebsiteOptions
{
    protected $_helper;
    protected $_option;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Dulv\SeparateAdminUser\Helper\Options $_option
    ){
        $this->_helper = $_helper;
        $this->_option = $_option;
    }
    public function afterToOptionArray(\Magento\CatalogRule\Model\Rule\WebsitesOptionsProvider $subject, $result)
    {
        if($this->_helper->isRootAdmin()){
            return $result;
        }
        return $this->_option->getWebsiteOption();
    }
}
