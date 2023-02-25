<?php
namespace Dulv\SeparateAdminUser\Plugin\Backend\Switcher;

class LimitGroups
{
    protected $_hepler;

    public function __construct(\Dulv\SeparateAdminUser\Helper\Data $_hepler)
    {
        $this->_hepler = $_hepler;
    }

    public function afterGetGroupCollection(\Magento\Store\Model\Website $subject, $result)
    {
        if($this->_hepler->isRootAdmin())
        {
            return $result;
        }
        $groups = $this->_hepler->getGroupIds();
        if(empty($groups))
        {
            $result->addFieldToFilter('group_id',-1);
            return $result;
        }
        $result->addFieldToFilter('group_id',['in'=>$groups]);
        return $result;
    }
}
