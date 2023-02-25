<?php

namespace Dulv\SeparateAdminUser\Plugin\Report\Product;

class LowstockGrid
{
    protected $_helper;
    public function __construct(\Dulv\SeparateAdminUser\Helper\Data $_helper){
        $this->_helper = $_helper;
    }
    public function beforeGetPreparedCollection(\Magento\Reports\Block\Adminhtml\Product\Lowstock\Grid $grid, $result)
    {
        $website = $grid->getRequest()->getParam('website');
        $group = $grid->getRequest()->getParam('group');
        $store = $grid->getRequest()->getParam('store');


    }
}
