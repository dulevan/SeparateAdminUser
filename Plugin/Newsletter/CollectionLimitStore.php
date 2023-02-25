<?php

namespace Dulv\SeparateAdminUser\Plugin\Newsletter;

class CollectionLimitStore
{
    const VALID_COLLECTION = 'newsletter_collection';
    protected $_helper;
    protected $_session;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Dulv\SeparateAdminUser\Model\Session $session
    ){
        $this->_helper = $_helper;
        $this->_session = $session;
    }

    protected function setValidStore(){
        $this->_session->setValideFilter(self::VALID_COLLECTION);
    }
    protected function isValideStore($clear=false){
        return $this->_session->isValidStoreFilter(self::VALID_COLLECTION, $clear);
    }

    public function addStoreFilter(\Mageno\Newsletter\Model\ResourceModel\Subscriber\Collection $collection, $storeIds)
    {
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $this->setValidStore();

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

    public function beforeGetSize(\Magento\Newsletter\Model\ResourceModel\Subscriber\Collection $collection)
    {
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $availableStoreIds  =$this->_helper->getStoreIds();
        if(empty($availableStoreIds)){
            $collection->addStoreFilter(-1);
            return null;
        }
        $collection->addStoreFilter($availableStoreIds);
        return null;
    }
}
