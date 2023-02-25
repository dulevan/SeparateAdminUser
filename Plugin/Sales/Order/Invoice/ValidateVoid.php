<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Order\Invoice;


class ValidateVoid extends ValidateInvoice
{

    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Order\Invoice\VoidAction $subject)
    {
        $this->performValidate($subject);
        return null;
    }
}
