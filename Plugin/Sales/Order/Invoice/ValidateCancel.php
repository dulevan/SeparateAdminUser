<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Order\Invoice;

class ValidateCancel extends ValidateInvoice
{

    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Order\Invoice\Cancel $subject)
    {
        $this->performValidate($subject);
        return null;
    }
}
