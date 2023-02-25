<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Shipment;

use Dulv\SeparateAdminUser\Helper\Data;

class ValidateShipment
{
    protected $_helper;
    protected $_objectManager;
    protected $_messageManager;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Magento\Framework\ObjectManagerInterface $_objectManager,
        \Magento\Framework\Message\ManagerInterface $_messageManager
    ){
        $this->_objectManager = $_objectManager;
        $this->_helper = $_helper;
        $this->_messageManager = $_messageManager;
    }

    protected function performValidate($subject)
    {
        $shipment_id = $subject->getRequest()->getParam('shipment_id',false);
        if(false == $shipment_id)
        {
            return null;
        }
        $shipment = $this->_objectManager->get(\Magento\Sales\Model\Order\Shipment::class)->load($shipment_id);

        if(!$shipment->getEntityId()){
            return null;
        }
        $availableStoreIds = $this->_helper->getStoreIds();
        if(empty($availableStoreIds)){
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('sales/shipment/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        if(!in_array($shipment->getStoreId(),$availableStoreIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('sales/shipment/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        return null;
    }
}
