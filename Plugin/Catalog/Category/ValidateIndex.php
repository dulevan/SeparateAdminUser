<?php
namespace Dulv\SeparateAdminUser\Plugin\Catalog\Category;

class ValidateIndex
{
    protected $_helper;
    protected $_messageManager;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->_helper = $_helper;
        $this->_messageManager = $messageManager;
    }

    public function beforeExecute(\Magento\Catalog\Controller\Adminhtml\Category\Index $subject){
        $storeId = $subject->getRequest()->getParam('store',false);
        if(!$this->_helper->isAdminhtml()){
            return null;
        }
        if($this->_helper->isRootAdmin()){
            return null;
        }
        $storeIds = $this->_helper->getStoreIds();
        if(is_null($storeIds))
        {
            $this->_messageManager->addErrorMessage(__("You are not administrator of this category"));
            $subject->getResponse()->setRedirect($subject->getUrl('/',['_current'=>true]));
            $subject->getResponse()->sendResponse();
            exit();
        }

        if(!in_array($storeId,$storeIds))
        {
            $subject->getResponse()->setRedirect($subject->getUrl('catalog/category/index',['_current'=>true,'store'=>$storeIds[0]]));
            $subject->getResponse()->sendResponse();
            exit();
        }
        return null;
    }
}
