<?php

namespace Dulv\SeparateAdminUser\Plugin\Sitemap;

class ValidateSitemap
{
    protected $_helper;
    protected $_sitemapModelFactory;

    protected $_sitemapResource;

    protected $_messageManager;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $helper,
        \Magento\Sitemap\Model\SitemapFactory $sitemapModelFactory,
        \Magento\Sitemap\Model\ResourceModel\SitemapFactory $sitemapResourceFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->_helper = $helper;
        $this->_sitemapModelFactory = $sitemapModelFactory;
        $this->_sitemapResource = $sitemapResourceFactory->create();
        $this->_messageManager = $messageManager;
    }
    public function performValidate($subject, $id=null)
    {
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        if(is_null($id))
        {
            $id = $subject->getRequest()->getParam('sitemap_id',false);
        }

        if(empty($id))
        {
            return null;
        }
        $model = $this->_sitemapModelFactory->create();
        $this->_sitemapResource->load($model, $id);
        if(!$model->getId())
        {
            return null;
        }

        $storeIds = $this->_helper->getStoreIds();
        if(empty($storeIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('admin/sitemap/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        if(!in_array($model->getStoreId(), $storeIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('admin/sitemap/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        return null;
    }
}
