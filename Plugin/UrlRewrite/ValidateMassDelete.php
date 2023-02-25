<?php

namespace Dulv\SeparateAdminUser\Plugin\UrlRewrite;

use Dulv\SeparateAdminUser\Helper\Data;

class ValidateMassDelete
{
    protected $_helper;
    protected $_filter;
    protected $_urlRewriteCollectionFactory;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $helper,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory $collectionFactory
    ){
        $this->_helper = $helper;
        $this->_filter = $filter;
        $this->_urlRewriteCollectionFactory = $collectionFactory;
    }

    public function beforeExecute(\Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite\MassDelete $subject){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $availableStoreIds = $this->_helper->getStoreIds();
        if(empty($availableStoreIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('admin/url_rewrite/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }

        $collection = $this->_filter->getCollection($this->_urlRewriteCollectionFactory->create());
        foreach ($collection as $item) {
            if (!in_array($item->getStoreId(), $availableStoreIds)) {
                $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
                $subject->getResponse()->setRedirect($subject->getUrl('admin/url_rewrite/index'));
                $subject->getResponse()->sendResponse();
                exit();
            }
        }
        return null;
    }
}
