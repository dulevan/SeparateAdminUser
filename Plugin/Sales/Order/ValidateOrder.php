<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Order;

class ValidateOrder
{
    protected $_helper;
    protected $_orderModelFactory;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order
     */
    protected $_orderResourceModel;

    protected $_messageManager;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $helper,
        \Magento\Sales\Model\OrderFactory $orderModelFactory,
        \Magento\Sales\Model\ResourceModel\OrderFactory $orderResourceFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->_helper = $helper;
        $this->_orderModelFactory = $orderModelFactory;
        $this->_orderResourceModel = $orderResourceFactory->create();
        $this->_messageManager = $messageManager;
    }
    public function processValidate($subject, $orderId=null)
    {
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        if(is_null($orderId))
        {
            $orderId = $subject->getRequest()->getParam('order_id',false);
        }

        if(empty($orderId))
        {
            return null;
        }
        $order = $this->_orderModelFactory->create();
        $this->_orderResourceModel->load($order, $orderId);
        if(!$order->getId())
        {
            return null;
        }

        $availableStoreIds = $this->_helper->getStoreIds();
        if(empty($availableStoreIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('sales/order/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        if(!in_array($order->getStoreId(), $availableStoreIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('sales/order/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        return null;
    }
}
