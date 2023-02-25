<?php

namespace Dulv\SeparateAdminUser\Plugin\Sales\Order\Invoice;

class ValidateAddComment extends ValidateInvoice
{
//    public function __construct(
//        \Dulv\SeparateAdminUser\Helper\Data $_helper,
//        \Magento\Sales\Api\InvoiceRepositoryInterface $invoiceRepository,
//        \Magento\Framework\Message\ManagerInterface $_messageManager
//    ){
//        parent::__construct($_helper, $invoiceRepository, $_messageManager);
//    }

    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Order\Invoice\AddComment $subject)
    {
        $subject->getRequest()->setParam('invoice_id', $subject->getRequest()->getParam('id'));
        $this->performValidate($subject);
        return null;
    }
}
