<?php
namespace Dulv\SeparateAdminUser\Plugin\Sales\Order\Edit;

use Dulv\SeparateAdminUser\Plugin\Sales\Order\ValidateOrder;

class ValidateStart extends ValidateOrder
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

    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Order\Edit\Start $subject)
    {
        $this->processValidate($subject);
        return null;
    }
}
