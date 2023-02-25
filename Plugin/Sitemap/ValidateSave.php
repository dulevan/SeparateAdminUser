<?php

namespace Dulv\SeparateAdminUser\Plugin\Sitemap;

class ValidateSave extends ValidateSitemap
{

    public function beforeExecute(\Magento\Sitemap\Controller\Adminhtml\Sitemap\Save $subject)
    {
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $id = $subject->getRequest()->getParam('sitemap_id',null);

        if(is_null($id) || (!is_null($id) && !$id))// new action
        {
            $storeId = $subject->getRequest()->getParam('store_id',null);
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

        $this->performValidate($subject);

        // validate if post data store_id is in my scope
        $storeId = $subject->getRequest()->getParam('store_id',null);

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
