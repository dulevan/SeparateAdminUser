<?php
namespace Dulv\SeparateAdminUser\Plugin\Sales\Order\Creditmemo;

use Dulv\SeparateAdminUser\Plugin\Sales\CreditMemo\ValidateCreditmemo;

class ValidateCancel extends ValidateCreditmemo
{

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Magento\Sales\Api\CreditmemoRepositoryInterface $_creditmemoRepository,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        parent::__construct($_helper, $_creditmemoRepository, $messageManager);
    }

    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Order\Creditmemo\Cancel $subject)
    {
        $this->performValidate($subject);
        return null;
    }
}
