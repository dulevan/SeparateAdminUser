<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Order\Invoice;

class ValidateCapture extends ValidateInvoice
{

    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Order\Invoice\Capture $subject)
    {
        $this->performValidate($subject);
        return null;
    }
}
