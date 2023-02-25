<?php

namespace Dulv\SeparateAdminUser\Plugin\Newsletter;

class ValidateMassUnsubscribe
{
    protected $_helper;
    protected $_collectionFactory;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory $_collectionFactory
    ){
        $this->_helper = $_helper;
        $this->_collectionFactory = $_collectionFactory;
    }

    public function beforeExecute(\Magento\Newsletter\Controller\Adminhtml\Subscriber\MassDelete $subject){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }

        $subscribersIds = $subject->getRequest()->getParam('subscriber');
        if(empty($subscribersIds))
        {
            return null;
        }
        $storeIds = $this->_helper->getStoreIds();
        if(empty($storeIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('newsletter/subscriber/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }

        $collection = $this->_collectionFactory->create();
        $collection->addFieldToFilter('subscriber_id',['in'=>$subscribersIds]);
        foreach ($collection as $item)
        {
            if(!in_array($item->getStoreId(), $storeIds))
            {
                $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
                $subject->getResponse()->setRedirect($subject->getUrl('newsletter/subscriber/index'));
                $subject->getResponse()->sendResponse();
                exit();
            }
        }
        return null;
    }
}
