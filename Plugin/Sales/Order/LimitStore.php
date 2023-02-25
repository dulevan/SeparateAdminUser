<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Order;

class LimitStore
{
    protected $_helper;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $helper
    )
    {
        $this->_helper = $helper;
    }
    public function beforeGetSize(\Magento\Sales\Model\ResourceModel\Order\Grid\Collection $collection)
    {
        if($this->_helper->isRootAdmin()){
            return null;
        }
        $storeIds = $this->_helper->getStoreIds();
        if(empty($storeIds)){
            $collection->addFieldToFilter('store_id',-1);
            return null;
        }
        $collection->addFieldToFilter('store_id',['in'=>$storeIds]);

        $this->_helper->logCheck($collection->getFilter('store_id'));

        return null;
    }

    public function beforeLoad(\Magento\Sales\Model\ResourceModel\Order\Grid\Collection $collection, $printQuery=null, $logQuery=null)
    {
        if($this->_helper->isRootAdmin()){
            return null;
        }
        $storeIds = $this->_helper->getStoreIds();
        if(empty($storeIds)){
            $collection->addFieldToFilter('store_id',-1);
            return null;
        }
        $collection->addFieldToFilter('store_id',['in'=>$storeIds]);

        return null;
    }
}
