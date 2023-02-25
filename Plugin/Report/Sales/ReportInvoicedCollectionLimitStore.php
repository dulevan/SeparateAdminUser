<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Sales;

class ReportInvoicedCollectionLimitStore extends \Dulv\SeparateAdminUser\Plugin\Collection\CollectionLimitStore
{

    public function beforeLoad(\Magento\Sales\Model\ResourceModel\Report\Invoiced\Collection\Invoiced $collection){
        return parent::performLimitAddStoreFilter($collection);
    }
}
