<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Sales;

class CouponReportCollection extends \Dulv\SeparateAdminUser\Plugin\Collection\CollectionLimitStore
{
    public function beforeLoad(\Magento\SalesRule\Model\ResourceModel\Report\Collection $collection){
        return parent::performLimitAddStoreFilter($collection);
    }
}
