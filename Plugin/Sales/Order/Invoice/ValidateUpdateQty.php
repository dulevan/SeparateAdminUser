<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Order\Invoice;


class ValidateUpdateQty extends ValidateInvoice
{

    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Order\Invoice\UpdateQty $subject)
    {
        $this->performValidate($subject);
        return null;
    }
}
