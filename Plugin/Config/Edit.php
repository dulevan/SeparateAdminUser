<?php

namespace Dulv\SeparateAdminUser\Plugin\Config;

class Edit
{
    protected $_helper;
    protected $_backendUrl;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Magento\Backend\Model\UrlInterface  $backendUrl
    ){
        $this->_helper = $_helper;
        $this->_backendUrl = $backendUrl;
    }
    public function beforeExecute(\Magento\Config\Controller\Adminhtml\System\Config\Edit $subject){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }

        $website = $subject->getRequest()->getParam('website',null);
        $store = $subject->getRequest()->getParam('store', null);

        $userWebsiteIds = $this->_helper->getWebsiteIds();
        $userStoreIds = $this->_helper->getStoreIds();

        if($this->_helper->isWebsiteUser())
        {

            if(!is_null($website) && in_array($website, $userWebsiteIds))
            {
                return null;
            }
            if(!is_null($store) && in_array($store, $userStoreIds))
            {
                return null;
            }
            $url = $this->_backendUrl->getCurrentUrl();
            if(!is_null($website))
            {
                $url = str_replace('/website/'.$website,'',$url);
            }
            if(!is_null($store))
            {
                $url = str_replace('/store/'.$store,'',$url);
            }


            if($url[strlen($url)-1] != '/'){
                $url = $url.'/';
            }
            $url = $url . 'website/'.$userWebsiteIds[0].'/';

            $subject->getResponse()->setRedirect($url);
            $subject->getResponse()->sendResponse();
            exit();
        }
        if($this->_helper->isGroupUser() || $this->_helper->isStoreUser())
        {
            if(!is_null($store) && in_array($store, $userStoreIds))
            {
                return null;
            }

            $url = $this->_backendUrl->getCurrentUrl();
            if(!is_null($website))
            {
                $url = str_replace('/website/'.$website,'',$url);
            }
            if(!is_null($store))
            {
                $url = str_replace('/store/'.$store,'',$url);
            }

            if($url[strlen($url)-1] != '/'){
                $url = $url.'/';
            }
            $url = $url . 'store/'.$userStoreIds[0].'/';
            $subject->getResponse()->setRedirect($url);
            $subject->getResponse()->sendResponse();
            exit();
        }
    }
}
