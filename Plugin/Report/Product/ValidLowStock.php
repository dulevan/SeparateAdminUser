<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Product;

class ValidLowStock
{
    protected $_helper;
    public function __construct(\Dulv\SeparateAdminUser\Helper\Data $_helper){
        $this->_helper = $_helper;
    }
    public function beforeExecute(\Magento\Reports\Controller\Adminhtml\Report\Product\Lowstock $subject){
        if($this->_helper->isRootAdmin())
        {
            return true;
        }
        $website = $subject->getRequest()->getParam('website', false);
        $group = $subject->getRequest()->getParam('group', false);
        $store = $subject->getRequest()->getParam('store', false);
        if($website !== false)
        {
            if(in_array($website, $this->_helper->getWebsiteIds()) )
            {
                return null;
            }
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('reports/report_product/lowstock'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        if($group !== false)
        {
            if(in_array($group, $this->_helper->getGroupIds()))
            {
                return null;
            }
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('reports/report_product/lowstock'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        if($store !== false)
        {
            if(in_array($store, $this->_helper->getStoreIds()))
            {
                return null;
            }
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('reports/report_product/lowstock'));
            $subject->getResponse()->sendResponse();
            exit();
        }

        if($this->_helper->isWebsiteUser()){
            $website = $this->_helper->getWebsiteIds();
            $subject->getRequest()->setParam('website',$website[0]);
            return null;
        }
        if($this->_helper->isGroupUser()){
            $group = $this->_helper->getGroupIds();
            $subject->getRequest()->setParam('group',$group[0]);
            return null;
        }
        if($this->_helper->isStoreUser())
        {
            $store = $this->_helper->getStoreIds();
            $subject->getRequest()->setParam('store', $store[0]);
            return null;
        }
    }
}
