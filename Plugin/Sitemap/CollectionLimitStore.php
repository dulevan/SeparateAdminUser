<?php

namespace Dulv\SeparateAdminUser\Plugin\Sitemap;

class CollectionLimitStore
{
    const VALID_SESSION_NAME = 'sitemap_collection';
    protected $_helper;

    protected $_validSession;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Dulv\SeparateAdminUser\Model\Session $_validSession
    ){
        $this->_helper = $_helper;
        $this->_validSession = $_validSession;
    }

    protected function setValidFilter()
    {
        $this->_validSession->setValideFilter(self::VALID_SESSION_NAME);
    }

    protected function getValidFilter($clear=false)
    {
        return $this->_validSession->getData(self::VALID_SESSION_NAME, $clear);
    }

    public function beforeAddStoreFilter(\Magento\Sitemap\Model\ResourceModel\Sitemap\Collection $collection, $storeIds){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $availableStoreIds = $this->_helper->getStoreIds();
        if(empty($availableStoreIds))
        {
            $this->setValidFilter();
            return ['storeIds'=>-1];
        }

        if(!is_array($storeIds))
        {
            $id = @intval($storeIds);
            if(!$id){
                $this->setValidFilter();
                return ['storeIds'=>-1];
            }
            $storeIds = [$id];
        }
        $validStoreIds = [];
        foreach ($storeIds as $id)
        {
            if(in_array($id, $storeIds))
            {
                $validStoreIds[] = $id;
            }
        }
        if(empty($validStoreIds))
        {
            $this->setValidFilter();
            return ['storeIds'=>-1];
        }
        $this->setValidFilter();
        return ['storeIds'=>$validStoreIds];
    }

    public function beforeLoad(\Magento\Sitemap\Model\ResourceModel\Sitemap\Collection $collection)
    {
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        if($this->getValidFilter(true)) //Checked valid in method addStoreFilter
        {
            return null;
        }
        $storeIds = $this->_helper->getStoreIds();
        if(empty($storeIds))
        {
            $collection->addFieldToFilter('store_id',-1);
            return null;
        }
        $collection->addFieldToFilter('store_id',['in'=>$storeIds]);
        return null;
    }
}
