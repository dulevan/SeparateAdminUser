<?php

namespace Dulv\SeparateAdminUser\Plugin\UrlRewrite;

class ValidateInlineEdit
{
    protected $_helper;

    protected $_messageManager;

    protected $_urlRewriteResourceModel;
    protected $_urlRewriteModelFactory;
    protected $_urlRewriteCollectionFactory;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $helper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\UrlRewrite\Model\UrlRewriteFactory $_modelFactory,
        \Magento\UrlRewrite\Model\ResourceModel\UrlRewriteFactory $urlResourceModelFactory,
        \Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory $urlRewriteCollectionFactory
    )
    {
        $this->_helper = $helper;
        $this->_messageManager = $messageManager;
        $this->_urlRewriteModelFactory = $_modelFactory;
        $this->_urlRewriteResourceModel = $urlResourceModelFactory->create();
        $this->_urlRewriteCollectionFactory = $urlRewriteCollectionFactory;
    }
    public function beforeExecute(\Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite\InlineEdit $subject){
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

        $postItems = $subject->getRequest()->getParam(
            'items',
            []
        );
        $ids = array_keys($postItems);
        $collection = $this->_urlRewriteCollectionFactory->create();
        $collection->addFieldToFilter('url_rewrite_id',$ids,);
        if($collection->getSize() != count($ids)){
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('admin/url_rewrite/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        foreach ($collection as $item){
            if(!in_array($item->getStoreId(), $availableStoreIds))
            {
                $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
                $subject->getResponse()->setRedirect($subject->getUrl('admin/url_rewrite/index'));
                $subject->getResponse()->sendResponse();
                exit();
            }
        }
        return null;
    }
}
