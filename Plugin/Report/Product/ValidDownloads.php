<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Product;

class ValidDownloads
{
    protected $_helper;
    public function __construct(\Dulv\SeparateAdminUser\Helper\Data $_helper)
    {
        $this->_helper = $_helper;
    }
    public function beforeExecute(
        \Magento\Reports\Controller\Adminhtml\Report\Product\Downloads $subject
    ){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $website = $subject->getRequest()->getParam('website',false);
        $group = $subject->getRequest()->getParam('group', false);
        $store = $subject->getRequest()->getParam('store', false);

        if($website !== false)
        {
            $websiteIds = $this->_helper->getWebsiteIds();
            if(in_array($website, $websiteIds))
            {
                return null;
            }
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('reports/report_product/downloads'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        if($group !== false)
        {
            $groupIds = $this->_helper->getGroupIds();
            if(in_array($group,$groupIds))
            {
                return null;
            }
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('reports/report_product/downloads'));
            $subject->getResponse()->sendResponse();
            exit();
        }

        if($store !== false)
        {
            $storeIds = $this->_helper->getStoreIds();
            if(in_array($store, $storeIds))
            {
                return null;
            }
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('reports/report_product/downloads'));
            $subject->getResponse()->sendResponse();
            exit();
        }

        if($this->_helper->isWebsiteUser())
        {
            $websiteIds = $this->_helper->getWebsiteIds();
            $subject->getRequest()->setParam('website',$websiteIds[0]);
            return null;
        }
        if($this->_helper->isGroupUser())
        {
            $groupIds = $this->_helper->getGroupIds();
            $subject->getRequest()->setParam('group', $groupIds[0]);
            return null;
        }
        if($this->_helper->getStoreIds())
        {
            $storeIds  = $this->_helper->getStoreIds();
            $subject->getRequest()->setParam('store',$storeIds[0]);
            return null;
        }
        return null;
    }
}
