<?php
namespace Dulv\SeparateAdminUser\Plugin\Cms\Block;

class ValidateIndex
{
    protected $helper;
    protected $session;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $helper,
        \Magento\Backend\Model\Auth\Session $session,
        \Psr\Log\LoggerInterface $logger
    ){
        $this->helper = $helper;
        $this->session = $session;
    }

    public function beforeAddStoreFilter(\Magento\Cms\Model\ResourceModel\Block\Collection $subject,$store,$withAdmin)
    {
        if(!$this->helper->isAdminhtml())
        {
            return null;
        }
        $user = $this->session->getUser();
        $website_id = $user->getData('website_id');
        $store_id = $user->getData('store_id');
        if(is_null($website_id) && is_null($store_id)){
            return null;
        }

        foreach ($store as $c => $val){
            $store = $val;
            break;
        }
        $stores = $this->helper->getStoreIds();
        if(is_null($stores)){
            return ['store'=>-1,'withAdmin'=>$withAdmin];
        }
        if(in_array($store, $stores)){//auto select one store that current user has permission
            $r = ['store'=>$store,'withAdmin'=>$withAdmin];
            return $r;
        }
        return ['store'=>-1,'withAdmin'=>$withAdmin];
    }
}
