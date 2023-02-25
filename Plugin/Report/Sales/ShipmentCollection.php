<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Sales;

class ShipmentCollection extends \Dulv\SeparateAdminUser\Plugin\Collection\CollectionLimitStore
{

    public function beforeLoad(\Magento\Sales\Model\ResourceModel\Report\Shipping\Collection\Shipment $collection){
        return parent::performLimitAddStoreFilter($collection);
    }
}
