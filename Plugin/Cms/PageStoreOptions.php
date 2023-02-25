<?php
namespace Dulv\SeparateAdminUser\Plugin\Cms;

class PageStoreOptions
{
    protected $helper;
    protected $session;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Options $helper,
        \Magento\Backend\Model\Auth\Session $session
    ){
        $this->helper = $helper;
        $this->session = $session;
    }
    public function afterToOptionArray(\Magento\Cms\Ui\Component\Listing\Column\Cms\Options $subject, $result)
    {
        $user = $this->session->getUser();
        $website_id = $user->getData('website_id');
        $store_id = $user->getData('store_id');
        if(is_null($website_id) && is_null($store_id)){
            return $result;
        }
        return $this->helper->getStoreOptions();
    }
}
