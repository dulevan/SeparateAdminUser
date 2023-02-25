<?php

namespace Dulv\SeparateAdminUser\Plugin\Search\Query;

class ValidateSearchQuery
{
    protected $_helper;
    protected $_queryModelFactory;

    protected $_queryResource;

    protected $_messageManager;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $helper,
        \Magento\Search\Model\QueryFactory $queryModelFactory,
        \Magento\Search\Model\ResourceModel\QueryFactory $queryResourceFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->_helper = $helper;
        $this->_queryModelFactory = $queryModelFactory;
        $this->_queryResource = $queryResourceFactory->create();
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
            $id = $subject->getRequest()->getParam('id',false);
        }

        if(empty($id))
        {
            return null;
        }
        $model = $this->_queryModelFactory->create();
        $this->_queryResource->load($model, $id);
        if(!$model->getId())
        {
            return null;
        }

        $storeIds = $this->_helper->getStoreIds();
        if(empty($storeIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('search/synonyms/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        if(!in_array($model->getStoreId(), $storeIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('search/synonyms/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        return null;
    }
}
