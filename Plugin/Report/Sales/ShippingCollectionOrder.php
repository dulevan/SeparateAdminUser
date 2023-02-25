<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Sales;

class ShippingCollectionOrder extends \Dulv\SeparateAdminUser\Plugin\Collection\CollectionLimitStore
{

    public function beforeLoad(\Magento\Sales\Model\ResourceModel\Report\Shipping\Collection\Order $collection){
        return parent::performLimitAddStoreFilter($collection);
    }
}
