<?php

namespace Dulv\SeparateAdminUser\Plugin\CatalogRule;

class LimitWebsiteCollection
{
    protected $_helper;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper
    ){
        $this->_helper = $_helper;
    }
    public function beforeLoad(\Magento\CatalogRule\Model\ResourceModel\Grid\Collection $collection){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $websiteId = $this->_helper->getWebsiteIds();
        if(-2 === $websiteId){
            return null;
        }
        $collection->addWebsiteFilter($websiteId);

        return null;
    }
}
