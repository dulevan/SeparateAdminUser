<?php
namespace Dulv\SeparateAdminUser\Plugin\User;

use Magento\Framework\Event\Observer;

class ForceFilterUser extends \Dulv\SeparateAdminUser\Plugin\User\User
{

    public function beforeGetSize(\Magento\User\Model\ResourceModel\User\Collection $collection){
        if($this->_helper->isRootAdmin()) {
            return null;
        }
        if($this->_helper->isWebsiteUser())
        {
            $groupIds = $this->_helper->getGroupIds();
            $collection->addFieldToFilter('group_id',['in'=>$groupIds]);
            return null;
        }
        if($this->_helper->isGroupUser())
        {
            $storeIds = $this->_helper->getStoreIds();
            $collection->addFieldToFilter('store_id',['in'=>$storeIds]);
            return null;
        }
        if($this->_helper->isStoreUser())
        {
            $collection->addFieldToFilter('store_id',-1);
            return null;
        }

        return null;
    }
}

