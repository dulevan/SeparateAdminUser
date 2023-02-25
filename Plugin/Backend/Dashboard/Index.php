<?php

namespace Dulv\SeparateAdminUser\Plugin\Backend\Dashboard;

class Index
{
    protected $_helper;
    protected $_backendUrl;
    protected $_messageManager;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Magento\Backend\Model\Url $_backendUrl,
        \Magento\Framework\Message\ManagerInterface $_messageManager
    ){
        $this->_helper = $_helper;
        $this->_backendUrl = $_backendUrl;
        $this->_messageManager = $_messageManager;
    }
    public function beforeExecute(\Magento\Backend\Controller\Adminhtml\Dashboard\Index $subject){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $storeIds = $this->_helper->getStoreIds();
        if(empty($storeIds)){
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('/'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        asort($storeIds, SORT_NUMERIC);
        $storeId = $subject->getRequest()->getParam('store',false);

        if(in_array($storeId, $storeIds))
        {
           return null;
        }
        $url = $this->_backendUrl->getCurrentUrl();
        $url = str_replace('/store/'.$storeId,'',$url);
        if($url[strlen($url)-1] != '/')
        {
            $url = $url .'/';
        }
        $url = $url . 'store/'.$storeIds[0];
        $subject->getResponse()->setRedirect($url);
        $subject->getResponse()->sendResponse();
        exit();
    }
}
