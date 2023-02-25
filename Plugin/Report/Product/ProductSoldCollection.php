<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Product;

class ProductSoldCollection
{
    const VALIDED_STOREIDS = 'product_sold_coll';
    protected $_helper;
    protected $_session;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Dulv\SeparateAdminUser\Model\Session $_session
    ){
        $this->_helper = $_helper;
        $this->_session = $_session;
    }
    protected function setValidedStore(){
        $this->_session->setValideFilter(self::VALIDED_STOREIDS);
    }
    protected function getValidedStore($clear=false){
        return $this->_session->isValidStoreFilter(self::VALIDED_STOREIDS, $clear);
    }
    public function beforeSetStoreIds(\Magento\Reports\Model\ResourceModel\Product\Sold\Collection $collection, $storeIds){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $this->setValidedStore();
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
            return ['storeIds' => -1];
        }
        if(is_array($storeIds))
        {
            $validStoreIds = [];
            foreach ($storeIds as $id)
            {
                if(in_array($id, $availableStoreIds))
                {
                    $validStoreIds[] = $id;
                }
            }
            if(empty($validStoreIds))
            {
                return ['storeIds' => -1];
            }
            return ['storeIds' => $validStoreIds];
        }
    }
    public function beforeLoad(\Magento\Reports\Model\ResourceModel\Product\Sold\Collection $collection){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        if($this->getValidedStore()){
            return null;
        }
        $availableStoreIds = $this->_helper->getStoreIds();
        if(empty($availableStoreIds))
        {
            $collection->setStoreIds(-1);
            return null;
        }
        $collection->setStoreIds($availableStoreIds);
        return null;
    }
    public function afterLoad(\Magento\Reports\Model\ResourceModel\Product\Sold\Collection $collection){
        $this->getValidedStore(true);
    }
}
