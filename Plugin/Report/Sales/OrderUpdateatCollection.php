<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Sales;

class OrderUpdateatCollection extends \Dulv\SeparateAdminUser\Plugin\Collection\CollectionLimitStore
{

    public function beforeLoad(\Magento\Sales\Model\ResourceModel\Report\Order\Updatedat\Collection $collection){
        return parent::performLimitAddStoreFilter($collection);
    }
}
