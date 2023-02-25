<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Invoice;

class LimitStoreCollection
{
    protected $_helper;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper
    ){
        $this->_helper = $_helper;
    }
    public function beforeLoad(\Magento\Sales\Model\ResourceModel\Order\Invoice\Collection $collection)
    {
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $availableStoreIds = $this->_helper->getStoreIds();
        if(empty($availableStoreIds))
        {
            $collection->addFieldToFilter('store_id',-1);
            return null;
        }
        $collection->addFieldToFilter('store_id',['in'=>$availableStoreIds]);
        return null;
    }
}
