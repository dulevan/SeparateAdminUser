<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Order\Invoice;

class ValidateEmail extends ValidateInvoice
{

    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Order\Invoice\Email $subject)
    {
        $this->performValidate($subject);
        return null;
    }
}
