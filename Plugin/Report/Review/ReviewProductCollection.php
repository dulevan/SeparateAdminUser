<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Review;

class ReviewProductCollection extends \Dulv\SeparateAdminUser\Plugin\Collection\CollectionLimitStore
{
    public function beforeGetSize(\Magento\Reports\Model\ResourceModel\Review\Product\Collection $collection){
        return parent::performLimitAddFieldToFilter($collection);
    }
}
