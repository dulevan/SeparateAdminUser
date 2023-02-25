<?php
namespace Dulv\SeparateAdminUser\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Escaper;

class Data extends AbstractHelper
{
    protected $_appState;
    /**
     * @var Escaper
     */
    protected $escaper;

    protected $authSession;
    protected $_logger;

    protected $_groupCollectionFactory;
    protected $_websiteCollectionFactory;
    protected $_storeCollectionFactory;

    protected static $websiteTree = [];


    protected $_websiteCollection = null;
    protected $_groupCollection = null;
    protected $_storeCollection = null;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\State $state,
        \Magento\Backend\Model\Auth\Session $session,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Store\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory,
        \Magento\Store\Model\ResourceModel\Website\CollectionFactory $websiteCollectionFactory,
        \Magento\Store\Model\ResourceModel\Store\CollectionFactory $storeCollectionFactory,
        Escaper $escaper
    )
    {
        $this->_appState = $state;
        $this->authSession = $session;
        $this->_logger = $logger;
        $this->escaper = $escaper;
        $this->_storeCollectionFactory = $storeCollectionFactory;
        $this->_websiteCollectionFactory = $websiteCollectionFactory;
        $this->_groupCollectionFactory = $groupCollectionFactory;
        parent::__construct($context);
        $this->buildWebsitesTree();
    }

    protected function buildWebsitesTree()
    {
        if(!empty(self::$websiteTree)){
            return ;
        }
        $websiteCollection = $this->_websiteCollectionFactory->create();
        $groupCollection = $this->_groupCollectionFactory->create();
        $storeCollection = $this->_storeCollectionFactory->create();

        foreach ($websiteCollection as $website)
        {
            self::$websiteTree[$website->getWebsiteId()] = [];
            foreach ($groupCollection as $group)
            {
                if($group->getWebsiteId() == $website->getWebsiteId()) {
                    self::$websiteTree[$website->getWebsiteId()][$group->getGroupId()] = [];
                    foreach ($storeCollection as $store)
                    {
                        if ($store->getGroupId() == $group->getGroupId())
                        {
                            self::$websiteTree[$website->getWebsiteId()][$group->getGroupId()][] = $store->getStoreId();
                        }
                    }
                }
            }
        }
    }
    public function getUser()
    {
        return $this->authSession->getUser();
    }
    public function isRootAdmin():bool
    {
        $user = $this->authSession->getUser();
        if(!$user)
        {
            if($this->isAdminhtml())
            {
                return true;
            }
            return false;
        }
        $website_id = $user->getWebsiteId();
        if(is_null($website_id) || (!is_null($website_id) && $website_id == 0)){
            return true;
        }
        return false;
    }

    public function isAdminhtml():bool
    {
        if(strcmp($this->_appState->getAreaCode(),\Magento\Framework\App\Area::AREA_ADMINHTML) === 0){
            return true;
        }
        return false;
    }

    public function getStoreIds()
    {
        $user = $this->authSession->getUser();

        $website_id = $user->getWebsiteId();
        $group_id = $user->getGroupId();
        $store_id = $user->getStoreId();

        if(!isset(self::$websiteTree[$website_id])){
            return null;
        }
        if(!is_null($group_id)){
            if(!is_null($store_id)){
                if(in_array($store_id,self::$websiteTree[$website_id][$group_id]))
                {
                    return [$store_id];
                }
                return [-1];
            }
            return self::$websiteTree[$website_id][$group_id];
        }else{
            $result = [];
            foreach (self::$websiteTree[$website_id] as $group)
            {
                $result = array_merge($result, $group);
            }

            return $result;
        }
        return null;
    }

    /**
     * Sanitize website/store option name
     *
     * @param string $name
     *
     * @return string
     */
    public function sanitizeName($name)
    {
        $matches = [];
        preg_match('/\$[:]*{(.)*}/', $name ?: '', $matches);
        if (count($matches) > 0) {
            $name = $this->escaper->escapeHtml($this->escaper->escapeJs($name));
        } else {
            $name = $this->escaper->escapeHtml($name);
        }

        return $name;
    }


    public function getAllGroupsForWebsite($website_id)
    {
        if(isset(self::$websiteTree[$website_id]))
        {
            return array_keys(self::$websiteTree[$website_id]);
        }
        return [];
    }

    public function getAllStoresForGroups($website_id, $group_id)
    {
        if(isset(self::$websiteTree[$website_id][$group_id]))
        {
            return self::$websiteTree[$website_id][$group_id];
        }
        return [];
    }

    public function getWebsiteIds(){
        $user = $this->authSession->getUser();
        return [$user->getWebsiteId()];
    }
    public function getGroupIds()
    {
        $user = $this->authSession->getUser();
        $websiteId = $user->getWebsiteId();
        if(!isset(self::$websiteTree[$websiteId]))
        {
            return false;
        }
        $groupId = $user->getGroupId();

        if(!is_null($groupId))
        {
            if(isset(self::$websiteTree[$websiteId][$groupId]))
            {
                return [$groupId];
            }
            return false;
        }
        return array_keys(self::$websiteTree[$websiteId]);
    }

    public function getWebsiteOptions(){
        $user = $this->authSession->getUser();
        $websiteId = $user->getWebsiteId();
        $websiteCollection = $this->_websiteCollectionFactory->create();
        $websiteCollection->addFieldToFilter('website_id',$websiteId);
        $options = [];
        foreach ($websiteCollection as $website){
            $options[$website->getId()] = $website->getName();
        }
        return $options;
    }

    /**
     * @return [$websiteCollection, $groupCollection, $storeCollection]
     */
    public function getCollections(){
        if(!is_null($this->_websiteCollection))
        {
            return [$this->_websiteCollection, $this->_groupCollection, $this->_storeCollection];
        }

        $websiteId = $this->getWebsiteIds();
        $groupIds = $this->getGroupIds();
        $storeIds = $this->getStoreIds();

        $this->_websiteCollection = $this->_websiteCollectionFactory->create();
        $this->_websiteCollection->addFieldToFilter('website_id',['in'=>$websiteId]);

        $this->_groupCollection = $this->_groupCollectionFactory->create();
        $this->_groupCollection->addWebsiteFilter($websiteId);
        $this->_groupCollection->addFieldToFilter('group_id',['in'=>$groupIds]);

        $this->_storeCollection = $this->_storeCollectionFactory->create();
        $this->_storeCollection->addWebsiteFilter($websiteId);
        $this->_storeCollection->addGroupFilter($groupIds);
        $this->_storeCollection->addFieldToFilter('store_id',['in'=>$storeIds]);
        return [$this->_websiteCollection, $this->_groupCollection, $this->_storeCollection];
    }
    public function isWebsiteUser(){
        $user = $this->authSession->getUser();
        if(!$user)
        {
            return false;
        }
        $websiteId = $user->getWebsiteId();
        $groupId = $user->getGroupId();
        $storeId = $user->getStoreId();

        if(!is_null($websiteId) && is_null($groupId) && is_null($storeId)){
            return true;
        }
        return false;
    }
    public function isGroupUser(){
        $user = $this->authSession->getUser();
        if(!$user){
            return false;
        }
        $websiteId = $user->getWebsiteId();
        $groupId = $user->getGroupId();
        $storeId = $user->getStoreId();
        if(!is_null($websiteId) && !is_null($groupId) && is_null($storeId)){
            return true;
        }
        return false;
    }

    /**
     * @Description Check if current login user belongs to Store View only
     * @return bool
     */
    public function isStoreUser(){
        $user = $this->authSession->getUser();
        if(!$user)
        {
            return false;
        }
        $websiteId = $user->getWebsiteId();
        $groupId = $user->getGroupId();
        $storeId = $user->getStoreId();
        if(!is_null($websiteId) && !is_null($groupId) && !is_null($storeId)){
            return true;
        }
        return false;
    }

    /**
     * @description Check if store_id is in my scopes
     * @param $storeId
     * @return void
     */
    public function isInMyScope($storeId){
        $storeids = $this->getStoreIds();
        if(is_array($storeids) && count($storeids)){
            return in_array($storeId, $storeids);
        }
        return false;
    }
    public function logCheck($data){
        if(is_array($data)){
            @ob_start();
            print_r($data);
            $content = ob_get_clean();
            $this->_logger->info($content);
        }else if(is_object($data)){
            @ob_start();
            print_r($data->getData());
            $content = ob_get_clean();
            $this->_logger->info($content);
        }else{
            $this->_logger->info($data);
        }
    }

    public function vardump($data){
        @ob_start();
        var_dump($data);
        $content = ob_get_clean();
        $this->_logger->info($content);
    }
}
