<?php
namespace Dulv\SeparateAdminUser\Plugin\User;


class ValidateSave extends ValidateUser
{

    public function beforeExecute(\Magento\User\Controller\Adminhtml\User\Save $subject){
        if($this->_helper->isStoreUser())
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('adminhtml/*/'));
            exit();
        }
        $user = $this->getCurrentUser();
        $cuser_website_id = $user->getData('website_id');
        $cuser_group_id = $user->getData('group_id');
        $cuser_store_id = $user->getData('store_id');
        $website_id = $subject->getRequest()->getParam('website_id');
        $parts = explode('.', $website_id);
        $website_id = $parts[0];
        $group_id = null;
        if(isset($parts[1])){
            $group_id = $parts[1];
        }
        $store_id = null;
        if(isset($parts[2])){
            $store_id = $parts[2];
        }
        if(!$this->validateWebsiteGroupStore($website_id, $group_id,$store_id)){
            $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('adminhtml/*/'));
            exit();
        }
        if( $this->_helper->isRootAdmin() )
        {
            //In case of root user
            $subject->getRequest()->setPostValue('website_id',$website_id);
            $subject->getRequest()->setPostValue('group_id',$group_id);
            $subject->getRequest()->setPostValue('store_id',$store_id);
            $subject->getRequest()->setPostValue('created_by_id', $user->getId());
            return null;
        }
        if($cuser_website_id != $website_id)
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('adminhtml/*/'));
            exit();
        }
        //User is belong to the website adding
        if(!is_null($cuser_group_id) ){//in case user belong to a group, he can create user for store view
            if($cuser_group_id == $group_id){
                if(!is_null($cuser_store_id)){
                    $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
                    $subject->getResponse()->setRedirect($subject->getUrl('adminhtml/*/'));
                    exit();
                }else{
                    $subject->getRequest()->setPostValue('website_id',$website_id);
                    $subject->getRequest()->setPostValue('group_id', $group_id);
                    $subject->getRequest()->setPostValue('store_id',$store_id);
                }
            }else{
                $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
                $subject->getResponse()->setRedirect($subject->getUrl('adminhtml/*/'));
                exit();
            }
        }else{
            $subject->getRequest()->setPostValue('website_id', $website_id);
            $subject->getRequest()->setPostValue('group_id', $group_id);
            $subject->getRequest()->setPostValue('store_id',$store_id);
        }

        $subject->getRequest()->setPostValue('created_by_id', $user->getId());
        return null;
    }
}
