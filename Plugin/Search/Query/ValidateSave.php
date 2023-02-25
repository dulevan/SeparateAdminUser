<?php

namespace Dulv\SeparateAdminUser\Plugin\Search\Query;

class ValidateSave extends ValidateSearchQuery
{
    public function beforeExecute(
        \Magento\Search\Controller\Adminhtml\Term\Save $subject
    ){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $id = $subject->getRequest()->getParam('id',null);
        if(is_null($id)){
            $id = $subject->getRequest()->getPost('query_id',null);
        }
        if(is_null($id) || (!is_null($id) && !$id)){// new action
            $storeId = $subject->getRequest()->getPost('store_id',null);
            if(is_null($storeId)){
                return null;
            }
            if($this->_helper->isInMyScope($storeId))
            {
                return null;
            }
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('search/term/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }

        //validate if origin model is in my scope
        $this->performValidate($subject, $id);

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
        $subject->getResponse()->setRedirect($subject->getUrl('search/term/index'));
        $subject->getResponse()->sendResponse();
        exit();
    }
}
