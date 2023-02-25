<?php

namespace Dulv\SeparateAdminUser\Plugin\Collection;

class CollectionLimitStore
{
    protected $_helper;
    public function __construct(\Dulv\SeparateAdminUser\Helper\Data $_helper){
        $this->_helper = $_helper;
    }

    protected function performLimitAddStoreFilter($collection){
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

    protected function performLimitAddFieldToFilter($collection){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $storeIds = $this->_helper->getStoreIds();
        if(empty($storeIds))
        {
            $collection->addFieldToFilter('store_id',-1);
            return null;
        }
        $collection->addFieldToFilter('store_id',['in'=>$storeIds]);
        return null;
    }
}
