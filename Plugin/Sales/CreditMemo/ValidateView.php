<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\CreditMemo;


class ValidateView extends ValidateCreditmemo
{
    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Creditmemo\View $subject)
    {
        $this->performValidate($subject);
        return null;
    }
}
