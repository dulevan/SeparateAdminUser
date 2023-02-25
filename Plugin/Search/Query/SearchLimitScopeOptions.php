<?php

namespace Dulv\SeparateAdminUser\Plugin\Search\Query;

class SearchLimitScopeOptions
{
    protected $_helper;
    protected $_optionHelper;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Dulv\SeparateAdminUser\Helper\Options $_optionHepler
    ){
        $this->_helper = $_helper;
        $this->_optionHelper = $_optionHepler;
    }

    public function afterToOptionArray(\Magento\Search\Ui\Component\Listing\Column\Scope\Options $subject, $result){
        if($this->_helper->isRootAdmin())
        {
            return $result;
        }
        return $this->_optionHelper->getWebsiteStoreOptions();
    }
}
