<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Customer;

class AccounCollectiontInitial
{
    protected $_helper;
    public function __construct(\Dulv\SeparateAdminUser\Helper\Data $_helper){
        $this->_helper = $_helper;
    }
    public function beforeSetStoreIds(\Magento\Reports\Model\ResourceModel\Accounts\Collection\Initial $collection, $storeIds){
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
                return $availableStoreIds;
            }
            return $validStoreIds;
        }
        return [-1];
    }

}
