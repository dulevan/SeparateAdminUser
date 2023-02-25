<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Order\Invoice;


class ValidateView extends ValidateInvoice
{

    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Order\Invoice\View $subject)
    {
        $this->performValidate($subject);
        return null;
    }
}
