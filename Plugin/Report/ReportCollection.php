<?php

namespace Dulv\SeparateAdminUser\Plugin\Report;

class ReportCollection
{
    protected $_helper;
    public function __construct(\Dulv\SeparateAdminUser\Helper\Data $_helper){
        $this->_helper = $_helper;
    }
    public function beforeLoad(\Magento\Tax\Model\ResourceModel\Report\Collection $collection){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $storeIds = $this->_helper->getStoreIds();
        if(empty($storeIds))
        {
            $collection->addStoreFilter(-1);
            return null;
        }
        $collection->addStoreFilter($storeIds);
        return null;
    }
}
