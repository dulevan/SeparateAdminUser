<?php

namespace Dulv\SeparateAdminUser\Plugin\User;

class User
{
    protected $_websiteCollectionFactory;
    protected $_groupCollectionFactory;
    protected $_storeCollectionFactory;

    protected $_helper;

    protected static $_websites = null;
    protected static $_groups = null;
    protected static $_stores = null;
    protected $_messageManager;

    public function __construct(
        \Magento\Store\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory,
        \Magento\Store\Model\ResourceModel\Website\CollectionFactory $websiteCollectionFactory,
        \Magento\Store\Model\ResourceModel\Store\CollectionFactory $storeCollectionFactory,
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->_websiteCollectionFactory = $websiteCollectionFactory;
        $this->_groupCollectionFactory = $groupCollectionFactory;
        $this->_storeCollectionFactory = $storeCollectionFactory;
        $this->_helper = $_helper;
        $this->_messageManager = $messageManager;
    }

    public function getWebsites()
    {
        if(!is_null(self::$_websites))
        {
            return self::$_websites;
        }
        self::$_websites = [];
        $collection = $this->_websiteCollectionFactory->create();
        foreach ($collection as $website)
        {
            self::$_websites[$website->getId()] = $website;
        }
        return self::$_websites;
    }

    public function getGroups()
    {
        if(!is_null(self::$_groups))
        {
            return self::$_groups;
        }
        self::$_groups = [];
        $collection = $this->_groupCollectionFactory->create();
        foreach ($collection as $group)
        {
            self::$_groups[$group->getId()] = $group;
        }
        return self::$_groups;
    }

    public function getStores()
    {
        if(!is_null(self::$_stores))
        {
            return self::$_stores;
        }
        $collection = $this->_storeCollectionFactory->create();
        foreach ($collection as $store)
        {
            self::$_stores[$store->getId()] = $store;
        }
        return self::$_stores;
    }

    public function getCurrentUser():\Magento\User\Model\User
    {
        return $this->_helper->getUser();
    }
}
