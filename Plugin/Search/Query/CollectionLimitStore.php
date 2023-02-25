<?php

namespace Dulv\SeparateAdminUser\Plugin\Search\Query;

class CollectionLimitStore
{
    protected $_helper;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper
    ){
        $this->_helper = $_helper;
    }

    public function beforeLoad(
        \Magento\Search\Model\ResourceModel\Query\Collection $subject
    ){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $storeIds = $this->_helper->getStoreIds();
        if(empty($storeIds)){
            $subject->addStoreFilter(-1);
            return null;
        }
        $subject->addStoreFilter($storeIds);
        return null;
    }
}
