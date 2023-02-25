<?php

namespace Dulv\SeparateAdminUser\Plugin\Search\Synonyms;

class ValidateSave extends ValidateSynonyms
{

    public function beforeExecute(\Magento\Search\Controller\Adminhtml\Synonyms\Save $subject){
        $groupId = $subject->getRequest()->getParam('group_id',null);
        if(is_null($groupId)){
            $groupId = $subject->getRequest()->getPost('group_id',null);
        }
        if(is_null($groupId) || (!is_null($groupId) && !$groupId)){// new action
            $storeId = $subject->getRequest()->getPost('store_id',null);
            if(is_null($storeId)){
                return null;
            }
            if($this->_helper->isInMyScope($storeId))
            {
                return null;
            }
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('search/synonyms/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        //
        $this->performValidate($subject, $groupId);

        // validate if post data store_id is in my scope
        $storeId = $subject->getRequest()->getPost('store_id',null);
        if(is_null($storeId)){
            return null;
        }
        if($this->_helper->isInMyScope($storeId))
        {
            return null;
        }
        $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
        $subject->getResponse()->setRedirect($subject->getUrl('search/synonyms/index'));
        $subject->getResponse()->sendResponse();
        exit();
    }
}
