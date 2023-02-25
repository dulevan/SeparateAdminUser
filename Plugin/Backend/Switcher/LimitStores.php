<?php
namespace Dulv\SeparateAdminUser\Plugin\Backend\Switcher;

class LimitStores
{
    protected $_hepler;

    public function __construct(\Dulv\SeparateAdminUser\Helper\Data $_hepler)
    {
        $this->_hepler = $_hepler;
    }

    public function afterGetStores(\Magento\Store\Model\Group $subject, $result)
    {
        if($this->_hepler->isRootAdmin())
        {
            return $result;
        }
        $storeIds = $this->_hepler->getStoreIds();

        if(empty($storeIds))
        {
            return [];
        }
        $new_result = [];
        foreach ($result as $storeId => $store)
        {
            if(in_array($storeId, $storeIds))
            {
                $new_result[$storeId] = $store;
            }
        }
        return $new_result;
    }
}
