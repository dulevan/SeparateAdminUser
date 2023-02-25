<?php

namespace Dulv\SeparateAdminUser\Plugin\SalesRule;

class LimitWebsiteOptions
{
    protected $_helper;
    public function __construct(\Dulv\SeparateAdminUser\Helper\Data $_helper)
    {
        $this->_helper = $_helper;
    }

    public function afterGetWebsiteIds(
        \Magento\SalesRule\Model\Data\Rule $subject,
        $result
    ){
        $this->_helper->logCheck($result);
        return $result;
    }
}
