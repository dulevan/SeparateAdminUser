<?php
namespace Dulv\SeparateAdminUser\Plugin\Cms\Page;

class ValidateEdit
{
    protected $_helper;
    protected $_pageResource;
    protected $_pageModelFactory;

    protected $_messageManager;


    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $heper,
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Cms\Model\ResourceModel\PageFactory $pageResourceFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->_helper = $heper;
        $this->_pageModelFactory = $pageFactory;
        $this->_pageResource = $pageResourceFactory->create();
        $this->_messageManager = $messageManager;
    }

    public function beforeExecute(\Magento\Cms\Controller\Adminhtml\Page\Edit $subject)
    {
        if($this->_helper->isRootAdmin()){
            return null;
        }
        $page_id = $subject->getRequest()->getParam('page_id',false);
        if(!$page_id){
            return null;
        }
        $model = $this->_pageModelFactory->create();
        $this->_pageResource->load($model,$page_id);
        $storeIds = $this->_helper->getStoreIds();
        if(is_null($storeIds)){
            $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('adminhtml/*/'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        if(!$model->getId() || $model->getId()!= $page_id){
            $this->_messageManager->addErrorMessage(__("You haven't select any page to edit"));
            $subject->getResponse()->setRedirect($subject->getUrl('adminhtml/*/',['_current'=>true]));
            $subject->getResponse()->sendResponse();
            exit();
        }
        $pageStoreIds = $model->getStoreId();
        foreach ($pageStoreIds as $id)
        {
            if(!in_array($id,$storeIds)){
                $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
                $subject->getResponse()->setRedirect($subject->getUrl('adminhtml/*/',['_current'=>true]));
                $subject->getResponse()->sendResponse();
                exit();
            }
        }
        return null;
    }
}
