<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Shipment;

class LimitStoreCollection
{
    protected $_helper;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper
    ){
        $this->_helper = $_helper;
    }

    public function beforeLoad(\Magento\Sales\Model\ResourceModel\Order\Shipment\Collection $subject)
    {
        $availableStoreIds = $this->_helper->getStoreIds();
        if(empty($availableStoreIds)){
            $subject->addFieldToFilter('store_id', -1);
            return null;
        }
        $subject->addFieldToFilter('store_id', ['in'=>$availableStoreIds]);
        return null;
    }
}
