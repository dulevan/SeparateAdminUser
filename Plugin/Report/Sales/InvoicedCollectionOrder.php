<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Sales;



class InvoicedCollectionOrder extends \Dulv\SeparateAdminUser\Plugin\Collection\CollectionLimitStore
{
    protected $_helper;
    public function __construct(\Dulv\SeparateAdminUser\Helper\Data $_helper){
        $this->_helper = $_helper;
    }
    public function beforeLoad(\Magento\Sales\Model\ResourceModel\Report\Invoiced\Collection\Order $collection){
        return parent::performLimitAddStoreFilter($collection);
    }
}
