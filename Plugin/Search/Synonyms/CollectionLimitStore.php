<?php

namespace Dulv\SeparateAdminUser\Plugin\Search\Synonyms;

class CollectionLimitStore
{
    protected $_helper;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper
    ){
        $this->_helper = $_helper;
    }
    public function beforeLoad(
        \Magento\Search\Model\ResourceModel\SynonymGroup\Collection $subject
    ){
        if($this->_helper->isRootAdmin()){
            return null;
        }
        $storeIds = $this->_helper->getStoreIds();
        $websiteIds = $this->_helper->getWebsiteIds();
        if(!$this->_helper->isStoreUser()){
            $storeIds[] = 0;
        }
        $subject->addFieldToFilter('store_id',['in'=>$storeIds]);
        $subject->addFieldToFilter('website_id',['in'=>$websiteIds]);
        return null;
    }
}
