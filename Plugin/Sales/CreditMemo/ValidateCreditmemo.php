<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\CreditMemo;

use Magento\Sales\Api\CreditmemoRepositoryInterface;

class ValidateCreditmemo
{
    protected $_creditmemoRepository;

    protected $_helper;

    protected $_messageManager;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Magento\Sales\Api\CreditmemoRepositoryInterface $_creditmemoRepository,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ){
        $this->_creditmemoRepository = $_creditmemoRepository;
        $this->_helper = $_helper;
        $this->_messageManager = $messageManager;
    }

    public function performValidate($subject)
    {
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $id = $subject->getRequest()->getParam('creditmemo_id',false);
        if(empty($id))
        {
            return null;
        }
        $creditmemo = $this->_creditmemoRepository->get($id);
        $this->_helper->logCheck(get_class($creditmemo));
        $storeId = $creditmemo->getStoreId();

        $availableStoreIds = $this->_helper->getStoreIds();
        if(empty($availableStoreIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('sales/order/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        if(!in_array($storeId, $availableStoreIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('sales/order/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        return null;
    }
}
