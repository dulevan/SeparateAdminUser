<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Sales;

class RefundedCollectionOrder extends \Dulv\SeparateAdminUser\Plugin\Collection\CollectionLimitStore
{
    public function beforeLoad(\Magento\Sales\Model\ResourceModel\Report\Refunded\Collection\Order $collection){
        return parent::performLimitAddStoreFilter($collection);
    }
}
