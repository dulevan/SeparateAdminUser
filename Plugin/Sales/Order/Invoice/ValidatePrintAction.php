<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Order\Invoice;


class ValidatePrintAction extends ValidateInvoice
{

    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Order\Invoice\PrintAction $subject)
    {
        $this->performValidate($subject);
        return null;
    }
}
