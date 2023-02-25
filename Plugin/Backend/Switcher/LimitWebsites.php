<?php
namespace Dulv\SeparateAdminUser\Plugin\Backend\Switcher;

class LimitWebsites
{
    protected $_helper;

    public function __construct(\Dulv\SeparateAdminUser\Helper\Data $_helper)
    {
        $this->_helper = $_helper;
    }

    public function beforeGetWebsites(\Magento\Backend\Block\Store\Switcher $subject)
    {
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $websiteId = $this->_helper->getWebsiteIds();
        $subject->setWebsiteIds($websiteId);
        return null;
    }

    public function afterIsWebsiteSwitchEnabled(\Magento\Backend\Block\Store\Switcher $subject, $result)
    {
        return $result;
//        if($this->_helper->isRootAdmin())
//        {
//            return $result;
//        }
//        if($this->_helper->isWebsiteUser())
//        {
//            return true;
//        }
//        return false;
    }
    public function afterIsStoreGroupSwitchEnabled(\Magento\Backend\Block\Store\Switcher $subject, $result)
    {
        return $result;
//        if($this->_helper->isRootAdmin())
//        {
//            return $result;
//        }
//        if($this->_helper->isGroupUser() || $this->_helper->isWebsiteUser())
//        {
//            return true;
//        }
//        return false;
    }
    public function afterIsStoreSwitchEnabled(\Magento\Backend\Block\Store\Switcher $subject, $result)
    {
        return $result;
//        if($this->_helper->isRootAdmin())
//        {
//            return $result;
//        }
//        if($this->_helper->isStoreUser() || $this->_helper->isGroupUser() || $this->_helper->isWebsiteUser())
//        {
//            return true;
//        }
//        return false;
    }
}
