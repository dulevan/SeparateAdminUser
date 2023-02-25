<?php

namespace Dulv\SeparateAdminUser\Plugin\Catalog\Product;

class ProductEditRemoveTabWebsite extends ValidateProduct
{

    public function afterModifyMeta(\Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\Websites $subject, $result)
    {
        if($this->_helper->isRootAdmin())
        {
            return $result;
        }
        $websiteIds = $this->_helper->getWebsiteIds();

        foreach ($result['websites']['children'] as $websiteId => $data) {
            if (!in_array($websiteId, $websiteIds)) {
                unset($result['websites']['children'] [$websiteId]);
            }
        }
        return $result;
    }
}
