<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Sales;

class OrderCollection extends \Dulv\SeparateAdminUser\Plugin\Collection\CollectionLimitStore
{

    public function beforeLoad(\Magento\Sales\Model\ResourceModel\Report\Order\Collection $collection){
        return parent::performLimitAddStoreFilter($collection);
    }
}
