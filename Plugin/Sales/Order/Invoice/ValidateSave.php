<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Order\Invoice;


class ValidateSave extends ValidateInvoice
{

    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Order\Invoice\Save $subject)
    {
        $this->performValidate($subject);
        return null;
    }
}
