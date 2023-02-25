<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Shipment;

class ValidateView extends ValidateShipment
{
    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Shipment\View $subject)
    {
        $this->performValidate($subject);
        return null;
    }
}
