<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Sales;

class CouponUpdatedAtCollection extends \Dulv\SeparateAdminUser\Plugin\Collection\CollectionLimitStore
{
    public function beforeLoad(\Magento\SalesRule\Model\ResourceModel\Report\Updatedat\Collection $collection){
        return parent::performLimitAddStoreFilter($collection);
    }
}
