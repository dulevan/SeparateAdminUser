<?php
namespace Dulv\SeparateAdminUser\Plugin\User;

class ValidateUser extends User
{
    protected $_userResourceModel;
    protected $_userFactory;

    public function __construct(
        \Magento\Store\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory,
        \Magento\Store\Model\ResourceModel\Website\CollectionFactory $websiteCollectionFactory,
        \Magento\Store\Model\ResourceModel\Store\CollectionFactory $storeCollectionFactory,
        \Dulv\SeparateAdminUser\Helper\Data $helper,
        \Magento\Framework\Message\ManagerInterface $messageManager,

        \Magento\User\Model\ResourceModel\UserFactory $userResourceModelFactory,
        \Magento\User\Model\UserFactory $userFactory
    )
    {
        $this->_userFactory = $userFactory;
        $this->_userResourceModel = $userResourceModelFactory->create();
        parent::__construct($groupCollectionFactory, $websiteCollectionFactory, $storeCollectionFactory, $helper, $messageManager);
    }
    public function validateWebsiteGroupStore($website_id, $group_id, $store_id)
    {
        $websites = $this->getWebsites();
        if(!isset($websites[$website_id])){
            return false;
        }
        if(is_null($group_id) || $group_id == 0){
            return true;
        }
        $groups = $this->getGroups();
        if(!isset($groups[$group_id])){
            return false;
        }

        if($groups[$group_id]->getWebsiteId() != $website_id){
            return false;
        }
        if(is_null($store_id) || $store_id == 0){
            return true;
        }

        $stores = $this->getStores();
        if(!isset($stores[$store_id])){
            return false;
        }
        if($stores[$store_id]->getGroupId() != $group_id){
            return false;
        }
        return true;
    }
    protected function isRootAdmin($user)
    {
        $websiteId = $user->getWebsiteId();
        $groupId = $user->getGroupId();
        $storeId = $user->getStoreId();
        return (is_null($websiteId) && is_null($groupId) && is_null($storeId));
    }
    protected function isWebsiteUser($user)
    {
        $websiteId = $user->getWebsiteId();
        $groupId = $user->getGroupId();
        $storeId = $user->getStoreId();
        return (!is_null($websiteId) && is_null($groupId) && is_null($storeId));

    }
    protected function isGroupUser($user)
    {
        $websiteId = $user->getWebsiteId();
        $groupId = $user->getGroupId();
        $storeId = $user->getStoreId();
        return (!is_null($websiteId) && !is_null($groupId) && is_null($storeId));
    }
    protected function isStoreUser($user)
    {
        $websiteId = $user->getWebsiteId();
        $groupId = $user->getGroupId();
        $storeId = $user->getStoreId();
        return (!is_null($websiteId) && !is_null($groupId) && !is_null($storeId));
    }

    protected function performValidateByLevel($subject)
    {
        if($this->_helper->isRootAdmin())
        {
            return null;
        }

        if($this->_helper->isStoreUser()){
            $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('admin/user/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        $user_id = $subject->getRequest()->getParam('user_id', false);
        if($user_id === false){
            return null;
        }
        $model = $this->_userFactory->create();
        $this->_userResourceModel->load($model, $user_id);
        if($model->isObjectNew()){
            $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('admin/user/index'))->sendResponse();
            exit();
        }
        if($this->isRootAdmin($model) || $this->isWebsiteUser($model))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('admin/user/index'))->sendResponse();
            exit();
        }


        if($this->_helper->isWebsiteUser() )
        {
            if($this->isGroupUser($model) )
            {
                if (in_array($model->getGroupId(), $this->_helper->getGroupIds())) {
                    return null;
                }
                $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
                $subject->getResponse()->setRedirect($subject->getUrl('admin/user/index'))->sendResponse();
                exit();
            }
            if($this->isStoreUser($model))
            {
                if(in_array($model->getStoreId(), $this->_helper->getStoreIds())){
                    return null;
                }
                $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
                $subject->getResponse()->setRedirect($subject->getUrl('admin/user/index'))->sendResponse();
                exit();
            }
        }
        if($this->_helper->isGroupUser())
        {
            if($this->isGroupUser($model))
            {
                $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
                $subject->getResponse()->setRedirect($subject->getUrl('admin/user/index'))->sendResponse();
                exit();
            }
            if($this->isStoreUser($model))
            {
                if(in_array($model->getStoreId(), $this->_helper->getStoreIds()))
                {
                    return null;
                }
                $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
                $subject->getResponse()->setRedirect($subject->getUrl('admin/user/index'))->sendResponse();
                exit();
            }
        }
        $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
        $subject->getResponse()->setRedirect($subject->getUrl('admin/user/index'))->sendResponse();
        exit();
    }

}
