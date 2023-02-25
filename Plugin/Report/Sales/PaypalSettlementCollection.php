<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Sales;

class PaypalSettlementCollection extends \Dulv\SeparateAdminUser\Plugin\Collection\CollectionLimitStore
{
    public function beforeLoad(Magento\Paypal\Model\ResourceModel\Report\Settlement\Row\Collection $collection){
        return parent::performLimitAddFieldToFilter($collection);
    }
}
