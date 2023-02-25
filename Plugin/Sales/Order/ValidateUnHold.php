<?php
namespace Dulv\SeparateAdminUser\Plugin\Sales\Order;

class ValidateUnHold extends ValidateOrder
{

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $helper,
        \Magento\Sales\Model\OrderFactory $orderModelFactory,
        \Magento\Sales\Model\ResourceModel\OrderFactory $orderResourceFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        parent::__construct($helper, $orderModelFactory, $orderResourceFactory, $messageManager);
    }

    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Order\UnHold $subject)
    {
        $this->processValidate($subject);
        return null;
    }
}
