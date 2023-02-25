<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Customer;

class CollectionInitial
{
    protected $_helper;
    public function __construct(\Dulv\SeparateAdminUser\Helper\Data $_helper){
        $this->_helper = $_helper;
    }
    public function afterGetStoreIds(\Magento\Reports\Model\ResourceModel\Customer\Totals\Collection\Initial $collection, $result){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $availableStoreIds = $this->_helper->getStoreIds();
        if(is_int($result))
        {
            if(in_array($result, $availableStoreIds))
            {
                return $result;
            }
            return $availableStoreIds;
        }
        if(is_array($result))
        {
            $storeIds = [];
            foreach ($result as $item)
            {
                if(in_array($item, $availableStoreIds))
                {
                    $storeIds[] = $item;
                }
            }
            if(empty($storeIds))
            {
                return $availableStoreIds;
            }
            return $storeIds;
        }
        return [-1];
    }
}
