<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Order;

class ValidateAddressSave extends ValidateOrder
{
    protected $_objectManager;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $helper,
        \Magento\Sales\Model\OrderFactory $orderModelFactory,
        \Magento\Sales\Model\ResourceModel\OrderFactory $orderResourceFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\ObjectManagerInterface $_objectManager
    ){
        $this->_objectManager = $_objectManager;
        parent::__construct($helper, $orderModelFactory, $orderResourceFactory, $messageManager);
    }
    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Order\AddressSave $subject)
    {
        $addressId = $this->getRequest()->getParam('address_id');
        $address = $this->_objectManager->create(\Magento\Sales\Model\Order\Address::class)->load($addressId);
        $orderId = $address->getParentId();

        $this->processValidate($subject, $orderId);
    }
}
