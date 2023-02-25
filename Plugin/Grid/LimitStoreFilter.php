<?php

namespace Dulv\SeparateAdminUser\Plugin\Grid;

class LimitStoreFilter
{
    protected $_websiteCollectionFactory;
    protected $_groupCollectionFactory;
    protected $_storeCollectionFactory;

    protected $_authSession;
    protected $_helper;

    public function __construct(
        \Magento\Store\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory,
        \Magento\Store\Model\ResourceModel\Website\CollectionFactory $websiteCollectionFactory,
        \Magento\Store\Model\ResourceModel\Store\CollectionFactory $storeCollectionFactory,
        \Magento\Backend\Model\Auth\Session $session,
        \Dulv\SeparateAdminUser\Helper\Data $helper
    ){
        $this->_helper = $helper;
        $this->_storeCollectionFactory = $storeCollectionFactory;
        $this->_websiteCollectionFactory = $websiteCollectionFactory;
        $this->_groupCollectionFactory = $groupCollectionFactory;
        $this->_authSession = $session;
    }

    public function afterGetHtml(\Magento\Backend\Block\Widget\Grid\Column\Filter\Store $filter, $result){
        if($this->_helper->isRootAdmin())
        {
            return $result;
        }
        $html = '<select class="admin__control-select" name="' .
                $filter->getColumn()->getId()
             . '" ' . $filter->getColumn()->getValidateClass() . $filter->getUiId(
                'filter',
                $filter->getColumn()->getId()
            ) . '>';
        $value = $filter->getColumn()->getValue();
        $html .= '<option value="0"'
            . ($value == 0 ? ' selected="selected"' : '') . '>'
            . __('All Your Store Views') . '</option>';


        list($websiteCollection, $groupCollection, $storeCollection) = $this->_helper->getCollections();

        foreach ($websiteCollection as $website) {
            $websiteShow = false;
            foreach ($groupCollection as $group) {
                if ($group->getWebsiteId() != $website->getId()) {
                    continue;
                }
                $groupShow = false;
                foreach ($storeCollection as $store) {
                    if ($store->getGroupId() != $group->getId()) {
                        continue;
                    }
                    if (!$websiteShow) {
                        $websiteShow = true;
                        $html .= '<optgroup label="' . $website->getName() . '"></optgroup>';
                    }
                    if (!$groupShow) {
                        $groupShow = true;
                        $html .= '<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;' . $group->getName() . '">';
                    }
                    $value = $filter->getValue();

                    $selected = (!empty($value) && $value == $store->getId()) ? ' selected="selected"' : '';
                    $html .= '<option value="' .
                        $store->getId() .
                        '"' .
                        $selected .
                        '>&nbsp;&nbsp;&nbsp;&nbsp;' . $store->getName() . '</option>';
                }
                if ($groupShow) {
                    $html .= '</optgroup>';
                }
            }
        }
        $html .= '</select>';
        return $html;
    }
}
