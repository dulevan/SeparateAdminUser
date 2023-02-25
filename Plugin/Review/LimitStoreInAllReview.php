<?php

namespace Dulv\SeparateAdminUser\Plugin\Review;

class LimitStoreInAllReview
{
    protected $_helper;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper
    ){
        $this->_helper = $_helper;
    }
    public function beforeGetSize(\Magento\Review\Model\ResourceModel\Review\Product\Collection $collection)
    {
        if ($this->_helper->isRootAdmin()) {
            return null;
        }

        $availableStoreIds = $this->_helper->getStoreIds();
        if (empty($availableStoreIds)) {
            $collection->setStoreFilter(-1);
            return null;
        }

        $collection->setStoreFilter($availableStoreIds);
        $collection->addStoreData();
        return null;
    }
}
