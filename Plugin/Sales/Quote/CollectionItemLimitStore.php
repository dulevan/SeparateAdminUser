<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Quote;

class CollectionItemLimitStore
{
    protected $_helper;
    protected $_session;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Dulv\SeparateAdminUser\Model\Session $session
    ){
        $this->_helper = $_helper;
        $this->_session = $session;
    }

    public function beforeAddStoreFilter(\Magento\Reports\Model\ResourceModel\Quote\Item\Collection $collection, $storeIds){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $availableStoreIds = $this->_helper->getStoreIds();
        if(empty($availableStoreIds))
        {
            return ['storeIds'=>-1];
        }
        if(is_int($storeIds))
        {
            if(in_array($storeIds, $availableStoreIds))
            {
                return null;
            }
            return ['storeIds'=>-1];
        }
        if(!is_array($storeIds)){
            return ['storeIds'=>-1];
        }
        $validIds = [];
        foreach ($storeIds as $id){
            if(in_array($id, $availableStoreIds))
            {
                $validIds[] = $id;
            }
        }
        if(empty($validIds))
        {
            return ['storeIds'=>-1];
        }
        return ['storeIds'=>$validIds];
    }

    public function beforeLoad(\Magento\Reports\Model\ResourceModel\Quote\Item\Collection $collection){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }

        $availabelStoreIds = $this->_helper->getStoreIds();
        if(empty($availabelStoreIds)){
            $collection->addStoreFilter(-1);
            return null;
        }
        $collection->addStoreFilter($availabelStoreIds);
        return null;
    }
}
