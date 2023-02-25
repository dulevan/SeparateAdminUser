<?php

namespace Dulv\SeparateAdminUser\Plugin\Backend\Dashboard;

class Url
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
    public function afterGetStartupPageUrl(\Magento\Backend\Model\Url $subject, $result){
        if($this->_helper->isRootAdmin())
        {
            return $result;
        }
        $storeIds = $this->_helper->getStoreIds();
        if(empty($storeIds)){
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('/'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        asort($storeIds, SORT_NUMERIC);
        return $subject->getUrl($result,['store'=>$storeIds[0]]);
    }
}
