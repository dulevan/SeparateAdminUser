<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Sales;

class RefundedCollection extends \Dulv\SeparateAdminUser\Plugin\Collection\CollectionLimitStore
{

    public function beforeLoad(\Magento\Sales\Model\ResourceModel\Report\Refunded\Collection\Refunded $collection){
        return parent::performLimitAddStoreFilter($collection);
    }
}
