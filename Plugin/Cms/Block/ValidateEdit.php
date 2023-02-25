<?php
namespace Dulv\SeparateAdminUser\Plugin\Cms\Block;

class ValidateEdit
{
    protected $_helper;
    protected $_blockResource;
    protected $_blockModelFactory;

    protected $_messageManager;


    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $heper,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Magento\Cms\Model\ResourceModel\BlockFactory $blockResourceFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->_helper = $heper;
        $this->_blockModelFactory = $blockFactory;
        $this->_blockResource = $blockResourceFactory->create();
        $this->_messageManager = $messageManager;
    }

    public function beforeExecute(\Magento\Cms\Controller\Adminhtml\Block\Edit $subject)
    {
        if($this->_helper->isRootAdmin()){
            return null;
        }
        $block_id = $subject->getRequest()->getParam('block_id',false);
        if(!$block_id){
            return null;
        }
        $model = $this->_blockModelFactory->create();
        $this->_blockResource->load($model,$block_id);
        $storeIds = $this->_helper->getStoreIds();
        if(is_null($storeIds)){
            $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('adminhtml/*/'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        if(!$model->getId() || $model->getId()!= $block_id){
            $this->_messageManager->addErrorMessage(__("You haven't select any page to edit"));
            $subject->getResponse()->setRedirect($subject->getUrl('adminhtml/*/',['_current'=>true]));
            $subject->getResponse()->sendResponse();
            exit();
        }
        $blockStoreIds = $model->getStoreId();
        foreach ($blockStoreIds as $id)
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
