<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Invoice;

use Dulv\SeparateAdminUser\Plugin\Sales\Order\Invoice\ValidateInvoice;

class ValidatePrint extends ValidateInvoice
{
    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Invoice\PrintAction $subject)
    {
        $this->performValidate($subject);
        return null;
    }
}
