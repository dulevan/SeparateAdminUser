<?php

namespace Dulv\SeparateAdminUser\Plugin\Catalog\Product;

class LimitWebsite
{
    protected $_helper;

    public function __construct(\Dulv\SeparateAdminUser\Helper\Data $_helper)
    {
        $this->_helper = $_helper;
    }

    public function beforeGetData(\Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider $productDataProvider)
    {
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $websiteIds = $this->_helper->getWebsiteIds();
        $websiteIds = implode(',',$websiteIds);
        /** @var\Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $productDataProvider->getCollection();
        //$collection->getSelect()->where('website_id in [2]');
        $collection->getSelect()->join(['pws'=>'catalog_product_website'],"(pws.product_id=e.entity_id) AND (pws.website_id IN ($websiteIds))");
        return null;
    }
}
