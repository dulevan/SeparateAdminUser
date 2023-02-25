<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Shipment;

class ValidatePrintAction extends ValidateShipment
{
    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Shipment\PrintAction $subject)
    {
        $this->performValidate($subject);
        return null;
    }
}
