<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Invoice;

use Dulv\SeparateAdminUser\Plugin\Sales\Order\Invoice\ValidateInvoice;

class ValidateEmail extends ValidateInvoice
{
    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Invoice\Email $subject)
    {
        $this->performValidate($subject);
        return null;
    }
}
