<?php

namespace Dulv\SeparateAdminUser\Plugin\Sitemap;

class ValidateMassDelete
{
    protected $_helper;

    protected $_sitemapCollectionFactory;
    protected $_sitemapModelFactory;
    protected $_sitemapResourceModel;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Magento\Sitemap\Model\ResourceModel\Sitemap\CollectionFactory $_collectionFactory,
        \Magento\Sitemap\Model\SitemapFactory $_sitemapModelFactory,
        \Magento\Sitemap\Model\ResourceModel\SitemapFactory $_sitemapResourceModelFactory
    ){
        $this->_helper = $_helper;
        $this->_sitemapCollectionFactory = $_collectionFactory;
        $this->_sitemapModelFactory = $_sitemapModelFactory;
        $this->_sitemapResourceModel = $_sitemapResourceModelFactory->create();
    }
    public function beforeExecute(\Magento\Search\Controller\Adminhtml\Term\MassDelete $subject){
        if($this->_helper->isRootAdmin())
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
        $excluded = $subject->getRequest()->getParam('excluded',null);
        $ids = [];
        if(is_null($excluded)){
            $ids = $subject->getRequest()->getParam('selected',[]);
        }

        if(is_null($excluded) && count($ids)){
            $collection = $this->_sitemapCollectionFactory->create();
            $collection->addFieldToFilter('sitemap_id',['in'=>$ids]);
            foreach ($collection as $item){
                $storeId = $item->getStoreId();
                if($storeId != 0 && !in_array($storeId, $storeIds) )
                {
                    $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
                    $subject->getResponse()->setRedirect($subject->getUrl('admin/sitemap/index'));
                    $subject->getResponse()->sendResponse();
                    exit();
                }
            }
        }
        return null;
    }
}
