<?php

namespace Dulv\SeparateAdminUser\Plugin\UrlRewrite;

class ValidateUrlRewrite
{
    protected $_helper;

    protected $_messageManager;

    protected $_urlRewriteResourceModel;
    protected $_urlRewriteModelFactory;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $helper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\UrlRewrite\Model\UrlRewriteFactory $_modelFactory,
        \Magento\UrlRewrite\Model\ResourceModel\UrlRewriteFactory $urlResourceModelFactory
    )
    {
        $this->_helper = $helper;
        $this->_messageManager = $messageManager;
        $this->_urlRewriteModelFactory = $_modelFactory;
        $this->_urlRewriteResourceModel = $urlResourceModelFactory->create();
    }
    public function performValidate($subject, $id=null)
    {
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        if(is_null($id))
        {
            $id = $subject->getRequest()->getParam('id',false);
        }

        if(empty($id))
        {
            return null;
        }
        $model = $this->_urlRewriteModelFactory->create();
        $this->_urlRewriteResourceModel->load($model, $id);
        if(!$model->getId())
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
        if(!in_array($model->getStoreId(), $availableStoreIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('admin/url_rewrite/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        return null;
    }
}
