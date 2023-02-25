<?php

namespace Dulv\SeparateAdminUser\Plugin\Newsletter;

class GridCollection
{
    protected $_helper;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper
    ){
        $this->_helper = $_helper;
    }
    public function beforeGetSize(\Magento\Newsletter\Model\ResourceModel\Grid\Collection $collection){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $availableStoreIds = $this->_helper->getStoreIds();
        if(empty($availableStoreIds))
        {
            $collection->getSelect()->where('subscriber.store_id in (?)',[-1]);
            return null;
        }
        $collection->getSelect()->where('subscriber.store_id in (?)',$availableStoreIds);
        return null;
    }
}
