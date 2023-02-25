<?php
namespace Dulv\SeparateAdminUser\Observer\Adminhtml\Product;

class GetProductId implements \Magento\Framework\Event\ObserverInterface
{
    protected $session;

    public function __construct(\Dulv\SeparateAdminUser\Model\Session $session)
    {
        $this->session = $session;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getData('product');
        $this->session->setData('sau_product_id',$product->getEntityId());
    }
}
