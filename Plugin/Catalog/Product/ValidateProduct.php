<?php

namespace Dulv\SeparateAdminUser\Plugin\Catalog\Product;

use Magento\Catalog\Model\ProductRepository;

class ValidateProduct
{
    protected $_helper;
    protected $_messageManager;
    protected $_productRepository;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ){
        $this->_helper = $_helper;
        $this->_productRepository = $productRepository;
        $this->_messageManager = $messageManager;
    }

    protected function performValidate($subject, $validateForNew= true)
    {
        $storeId = $subject->getRequest()->getParam('store',false);
        if(false === $storeId){
            if($validateForNew)
            {
                $id = $subject->getRequest()->getParam('id',false);
                if(false === $id)
                {
                    return null;
                }
            }
            return $this->selectCorrectStore($subject);
        }


            return $this->validateStoreLevel($subject, $storeId);

        return null;
    }
    protected function selectCorrectStore($subject){
        $storeIds = $this->_helper->getStoreIds();
        $id = $subject->getRequest()->getParam('id',0);

        $subject->getResponse()->setRedirect($subject->getUrl('catalog/product/edit',['store'=>$storeIds[0],'id'=>$id]));
        $subject->getResponse()->sendResponse();
        exit();

    }

    protected function validateStoreLevel($subject,$storeId)
    {
        $storeIds = $this->getStoreIds($subject);
        if(!in_array($storeId, $storeIds))
        {
            $this->_messageManager->addErrorMessage(__("You are not administrator of this product"));
            $subject->getResponse()->setRedirect($subject->getUrl('catalog/product/index/',['_current'=>true]));
            $subject->getResponse()->sendResponse();
            exit();
        }
    }

    protected function getStoreIds($subject)
    {
        $storeIds = $this->_helper->getStoreIds();
        if(is_null($storeIds))
        {
            $this->_messageManager->addErrorMessage(__("You are not administrator of this product"));
            $subject->getResponse()->setRedirect($subject->getUrl('catalog/product/index/',['_current'=>true]));
            $subject->getResponse()->sendResponse();
            exit();
        }
        return $storeIds;
    }


    public function getWebsiteIds($subject)
    {
        $websiteIds = $this->_helper->getWebsiteIds();
        if(is_null($websiteIds))
        {
            $this->_messageManager->addErrorMessage(__("You are not administrator of this product"));
            $subject->getResponse()->setRedirect($subject->getUrl('catalog/product/index/',['_current'=>true]));
            $subject->getResponse()->sendResponse();
            exit();
        }
        return $websiteIds;
    }

    protected function getProduct($id)
    {
        return $this->_productRepository->getById($id);
    }
}
