<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Order\Invoice;

class ValidateInvoice
{
    protected $_helper;
    protected $_invoiceRepository;
    protected $_messageManager;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Magento\Sales\Api\InvoiceRepositoryInterface $invoiceRepository,
        \Magento\Framework\Message\ManagerInterface $_messageManager
    ){
        $this->_helper = $_helper;
        $this->_invoiceRepository = $invoiceRepository;
        $this->_messageManager = $_messageManager;
    }

    public function performValidate($subject)
    {
        if($this->_helper->isRootAdmin())
        {
            return null;
        }

        $invoide = $this->getInvoide($subject);
        if($invoide === false || (!$invoide->getId()))
        {
            return null;
        }

        $availableStoreIds = $this->_helper->getStoreIds();
        if(empty($availableStoreIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('sales/invoice/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        if(!in_array($invoide->getStoreId(), $availableStoreIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('sales/invoice/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        return null;
    }
    protected function getInvoide($subject)
    {
        try {
            $invoice = $this->_invoiceRepository->get($subject->getRequest()->getParam('invoice_id'));
        } catch (\Exception $e) {
            $this->_messageManager->addErrorMessage(__('Invoice capturing error'));
            return false;
        }

        return $invoice;
    }
}
