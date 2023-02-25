<?php

namespace Dulv\SeparateAdminUser\Plugin\UrlRewrite;

class ValidateSave
{
    protected $_helper;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $helper
    ){
        $this->_helper = $helper;
    }
    public function beforeExecute(\Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite\Save $subject){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $storeIds = $this->_helper->getStoreIds();
        if(empty($storeIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('admin/url_rewrite/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        $storeId = $subject->getRequest()->getParam('store_id',false);
        if(!in_array($storeId, $storeIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('admin/url_rewrite/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        return null;
    }
}
