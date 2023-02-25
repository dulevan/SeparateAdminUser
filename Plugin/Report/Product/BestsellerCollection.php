<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Product;

class BestsellerCollection
{
    protected $_helper;
    public function __construct(\Dulv\SeparateAdminUser\Helper\Data $_helper){
        $this->_helper = $_helper;
    }

    public function beforeAddStoreRestrictions(\Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection $collection, $storeIds)
    {
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $availableStoreIds = $this->_helper->getStoreIds();
        if(is_int($storeIds))
        {
            if(in_array($storeIds, $availableStoreIds))
            {
                return null;
            }
            return ['storeIds'=>$availableStoreIds];
        }
        if(is_array($storeIds))
        {
            $validStoreIds = [];
            foreach ($storeIds as $item)
            {
                if(in_array($item, $availableStoreIds))
                {
                    $validStoreIds[] = $item;
                }
            }
            if(empty($validStoreIds))
            {
                return ['storeIds'=>$availableStoreIds];
            }
            return ['storeIds'=>$validStoreIds];
        }
        return ['storeIds'=>-1];
    }
}
