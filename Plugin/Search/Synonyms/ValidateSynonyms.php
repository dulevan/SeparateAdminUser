<?php

namespace Dulv\SeparateAdminUser\Plugin\Search\Synonyms;

class ValidateSynonyms
{
    protected $_helper;
    protected $_synonymGroupModelFactory;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order
     */
    protected $_synonymGroupResource;

    protected $_messageManager;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $helper,
        \Magento\Search\Model\SynonymGroupFactory $synonymGroupModelFactory,
        \Magento\Search\Model\ResourceModel\SynonymGroupFactory $synonymGroupResourceFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->_helper = $helper;
        $this->_synonymGroupModelFactory = $synonymGroupModelFactory;
        $this->_synonymGroupResource = $synonymGroupResourceFactory->create();
        $this->_messageManager = $messageManager;
    }
    public function performValidate($subject, $groupId=null)
    {
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        if(is_null($groupId))
        {
            $groupId = $subject->getRequest()->getParam('group_id',false);
        }

        if(empty($groupId))
        {
            return null;
        }
        $model = $this->_synonymGroupModelFactory->create();
        $this->_synonymGroupResource->load($model, $groupId);
        if(!$model->getId())
        {
            return null;
        }

        $availableStoreIds = $this->_helper->getStoreIds();
        if(empty($availableStoreIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('search/synonyms/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        if(!in_array($model->getStoreId(), $availableStoreIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('search/synonyms/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        return null;
    }
}
