<?php
namespace Dulv\SeparateAdminUser\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Escaper;

class User extends AbstractHelper
{
    protected $_authSession;
    protected $_storeWebsiteRelation;
    protected $_logger;
    protected $_systemStore;
    protected $_escaper;
    protected $_helper;
    /**
     * @var [
         *      [
         *          'label' => ....,
         *          'value' => .....
         *      ]
     *      ]
     */
    protected $_storeOptionsArray = null;

    /**
     * @var
     *      [
     *          [value => label]
     *      ]
     */
    protected $_optionsArray = null;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Backend\Model\Auth\Session $session,
        \Magento\Store\Model\ResourceModel\StoreWebsiteRelation $storeWebsiteRelation,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Store\Model\System\Store $systemStore,
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        Escaper $escaper
    )
    {
        $this->_authSession = $session;
        $this->_storeWebsiteRelation = $storeWebsiteRelation;
        $this->_logger = $logger;
        $this->_systemStore = $systemStore;
        $this->_helper = $_helper;
        $this->_escaper = $escaper;
        parent::__construct($context);
    }

    /**
     * Get Options Array of Website/Store/StoreView to
     * display in Craete user form
     * @return array|mixed|null
     */
    public function getStoreOptionsArray(){
        if(!is_null($this->_storeOptionsArray)) {
            return $this->_storeOptionsArray;
        }
        $this->_storeOptionsArray = [];

        $user = $this->_authSession->getUser();
        $website_id = $user->getData('website_id');
        $group_id = $user->getData('group_id');
        $store_id = $user->getData('store_id');

        if(!is_null($store_id)){
            $this->_storeOptionsArray[] = [
                'label' => __("You don't have permission to create user"),
                'value' =>'-1'
            ];
            return $this->_storeOptionsArray;
        }


        if( is_null($website_id) || (!is_null($website_id) && $website_id == 0)) {//root user
            $this->_storeOptionsArray['All Store Views']['label'] = __('All Store Views');
            $this->_storeOptionsArray['All Store Views']['value'] = '';
            return $this->getAllWebsiteOptionsArray();
        }else{//website user
            return $this->getWebsiteOptionsArray($website_id, $group_id);
        }
    }
    public function getAllWebsiteOptionsArray(){
        $websiteCollection = $this->_systemStore->getWebsiteCollection();
        $groupCollection = $this->_systemStore->getGroupCollection();
        $storeCollection = $this->_systemStore->getStoreCollection();

        foreach ($websiteCollection as $website) {
            $this->_storeOptionsArray[] = [
                'label' => $this->_helper->sanitizeName($website->getName()),
                'value' => $website->getId()
            ];
            foreach ($groupCollection as $group) {
                if ($group->getWebsiteId() === $website->getId()) {
                    $this->_storeOptionsArray[] = [
                        'label' => '|____'.$this->_helper->sanitizeName($group->getName()),
                        'value' => $website->getId().'.'.$group->getId()
                    ];
                    foreach ($storeCollection as $store) {
                        if ($store->getGroupId() === $group->getId()) {
                            $this->_storeOptionsArray[] = [
                                'label' => '____|____' . $this->_helper->sanitizeName($store->getName()),
                                'value' => $website->getId().'.'.$group->getId().'.'.$store->getId()
                            ];
                        }
                    }
                }
            }
        }
        return $this->_storeOptionsArray;
    }

    public function getWebsiteOptionsArray($website_id, $group_id){
        $websiteCollection = $this->_systemStore->getWebsiteCollection();
        if(!isset($websiteCollection[$website_id])){
            $this->_storeOptionsArray = [];
            return $this->_storeOptionsArray;
        }
        $website = $websiteCollection[$website_id];
        $groupCollection = $this->_systemStore->getGroupCollection();
        $storeCollection = $this->_systemStore->getStoreCollection();

        $groups = [];
        foreach ($groupCollection as $group)
        {
            if(is_null($group_id)) {//In this case, User belong to website
                if ($group->getWebsiteId() === $website->getId())
                {
                    $stores = [];
                    foreach ($storeCollection as $store)
                    {
                        if ($store->getGroupId() === $group->getId())
                        {
                            $stores[] = [
                                'label' => '____|____' . $this->_helper->sanitizeName($store->getName()),
                                'value' => $website->getId() . '.' . $group->getId() . '.' . $store->getId(),
                            ];
                        }
                    }
                    $groups[] = [
                            'label' => '|____' . $this->_helper->sanitizeName($group->getName()),
                            'value' => '' . $website->getId() . '.' . $group->getId()
                        ];
                    foreach ($stores as $store){
                        $groups[] = [
                            'label' => $store['label'],
                            'value' => $store['value']
                        ];
                    }
                }
            }else{
                if($group->getId() == $group_id){
                    $stores = [];
                    foreach ($storeCollection as $store)
                    {
                        if ($store->getGroupId() === $group->getId())
                        {
                            $stores[] = [
                                'label' => '|____' . $this->_helper->sanitizeName($store->getName()),
                                'value' => $website->getId() . '.' . $group->getId() . '.' . $store->getId(),
                            ];
                        }
                    }
                    if(!empty($stores)) {
                        $groups[] = [
                            'label' => $this->_helper->sanitizeName($group->getName()),
                            'value' => array_values($stores)
                        ];
                    }else{
                        $groups[] = [
                            'label' => $this->_helper->sanitizeName($group->getName()),
                            'value' => []
                        ];
                    }
                }
            }
        }
        if(!empty($groups)) {
            $this->_storeOptionsArray[] = [
                'label' => $this->_helper->sanitizeName($website->getName()),
                'value' => array_values($groups)
            ];
        }else{
            $this->_storeOptionsArray[] = [
                'label' => $this->_helper->sanitizeName($website->getName()),
                'value' => []
            ];
        }
        return $this->_storeOptionsArray;
    }
/***************************************************************/
    /**
     * Get Options Array of Website/Store/StoreView to
     * display in Craete user form
     * @return array|mixed|null
     */
    public function getAllOptionsArray(){
        if(!is_null($this->_optionsArray)) {
            return $this->_optionsArray;
        }
        $this->_optionsArray = [];

        $user = $this->_authSession->getUser();
        $website_id = $user->getData('website_id');
        $group_id = $user->getData('group_id');
        $store_id = $user->getData('store_id');

        if(!is_null($store_id)){
            $this->_optionsArray[] = [
                'label' => __("You don't have permission to create user"),
                'value' =>'-1'
            ];
            return $this->_optionsArray;
        }


        if( is_null($website_id) || (!is_null($website_id) && $website_id == 0)) {//root user
            $this->_optionsArray['All Store Views']['label'] = __('All Store Views');
            $this->_optionsArray['All Store Views']['value'] = '';
            return $this->getAllStoreOptionsArray();
        }else{//website user
            return $this->getAllUserStoreOptionsArray($website_id, $group_id);
        }
    }
    public function getAllStoreOptionsArray(){
        $websiteCollection = $this->_systemStore->getWebsiteCollection();
        $groupCollection = $this->_systemStore->getGroupCollection();
        $storeCollection = $this->_systemStore->getStoreCollection();

        foreach ($websiteCollection as $website) {
            $this->_optionsArray[] = [
                $website->getId() => $this->_helper->sanitizeName($website->getName())
            ];
            foreach ($groupCollection as $group) {
                if ($group->getWebsiteId() === $website->getId()) {
                    $this->_optionsArray[] = [
                        $website->getId().'.'.$group->getId() => '|____'.$this->_helper->sanitizeName($group->getName())
                    ];
                    foreach ($storeCollection as $store) {
                        if ($store->getGroupId() === $group->getId()) {
                            $this->_optionsArray[] = [
                                $website->getId().'.'.$group->getId().'.'.$store->getId() => '____|____' . $this->_helper->sanitizeName($store->getName())
                            ];
                        }
                    }
                }
            }
        }
        $this->logCheck($this->_optionsArray);
        return $this->_optionsArray;
    }

    public function getAllUserStoreOptionsArray($website_id, $group_id){
        $websiteCollection = $this->_systemStore->getWebsiteCollection();
        if(!isset($websiteCollection[$website_id])){
            $this->_optionsArray = [];
            return $this->_optionsArray;
        }
        $website = $websiteCollection[$website_id];
        $groupCollection = $this->_systemStore->getGroupCollection();
        $storeCollection = $this->_systemStore->getStoreCollection();

        $groups = [];
        foreach ($groupCollection as $group)
        {
            if(is_null($group_id)) {//In this case, User belong to website
                if ($group->getWebsiteId() === $website->getId())
                {
                    $stores = [];
                    foreach ($storeCollection as $store)
                    {
                        if ($store->getGroupId() === $group->getId())
                        {
                            $stores[] = [
                                $website->getId() . '.' . $group->getId() . '.' . $store->getId() => '____|____' . $this->_helper->sanitizeName($store->getName())
                            ];
                        }
                    }
                    $groups[] = [
                        $website->getId() . '.' . $group->getId() => '|____' . $this->_helper->sanitizeName($group->getName())
                    ];
                    $groups = $groups + $stores;
                }
            }else{
                if($group->getId() == $group_id){
                    $stores = [];
                    foreach ($storeCollection as $store)
                    {
                        if ($store->getGroupId() === $group->getId())
                        {
                            $stores[] = [
                                $website->getId() . '.' . $group->getId() . '.' . $store->getId() => '|____' . $this->_helper->sanitizeName($store->getName())
                            ];
                        }
                    }
                    if(!empty($stores)) {
                        $groups = $groups + $stores;
                    }else{
                        $groups[] = [
                            '' => $this->_helper->sanitizeName($group->getName())
                        ];
                    }
                }
            }
        }
        if(!empty($groups)) {
            $this->_optionsArray[] = $groups;
        }else{
            $this->_optionsArray[] = [
                '' => $this->_helper->sanitizeName($website->getName())
            ];
        }
        $this->logCheck($this->_optionsArray);
        return $this->_optionsArray;
    }

    protected function logCheck($data){
        if(is_array($data)){
            @ob_start();
            print_r($data);
            $content = ob_get_clean();
            $this->_logger->info($content);
        }else{
            $this->_logger->info($data);
        }
    }
}
