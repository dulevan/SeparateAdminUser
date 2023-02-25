<?php
namespace Dulv\SeparateAdminUser\Plugin\Catalog\Category;

class ValidateDelete
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

    public function beforeExecute(\Magento\Catalog\Controller\Adminhtml\Category\Delete $subject){
        $storeId = $subject->getRequest()->getParam('store',false);
        if(!$this->_helper->isAdminhtml()){
            return null;
        }
        if($this->_helper->isRootAdmin()){
            return null;
        }
        $storeIds = $this->_helper->getStoreIds();

        $this->_messageManager->addErrorMessage(__("You are not administrator of this category"));
        if(is_null($storeIds)) {
            $subject->getResponse()->setRedirect($subject->getUrl('adminhtml/catalog/category/index', ['_current' => true]));
        }else{
            $subject->getResponse()->setRedirect($subject->getUrl('adminhtml/catalog/category/index', ['_current' => true,'store'=>$storeIds[0]]));
        }
        $subject->getResponse()->sendResponse();
        exit();
    }
}
