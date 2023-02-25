<?php
namespace Dulv\SeparateAdminUser\Plugin\Cms\Page;

class ValidateIndex
{
    protected $_helper;
    protected $session;
    protected $logger;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $helper,
        \Magento\Backend\Model\Auth\Session $session,
        \Psr\Log\LoggerInterface $logger
    ){
        $this->_helper = $helper;
        $this->session = $session;
        $this->logger= $logger;
    }

    public function beforeAddStoreFilter(\Magento\Cms\Model\ResourceModel\Page\Collection $subject,$store,$withAdmin)
    {
        if(is_null($store)){
            return null;
        }
        if(!$this->_helper->isAdminhtml()){
            return null;
        }
        $user = $this->session->getUser();
        $website_id = $user->getData('website_id');
        $store_id = $user->getData('store_id');
        if(is_null($website_id) && is_null($store_id)){
            return null;
        }
        if(!is_array($store))
        {
            $store = [$store];
        }
        foreach ($store as $c => $val){
            $store = $val;
            break;
        }
        $stores = $this->_helper->getStoreIds();
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
