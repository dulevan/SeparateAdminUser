<?php
namespace Dulv\SeparateAdminUser\Plugin\Catalog\Product;

class ValidateSave extends ValidateProduct
{
    protected $_helperSession;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Dulv\SeparateAdminUser\Model\Session $_helperSession
    )
    {
        parent::__construct($_helper, $productRepository, $messageManager);
        $this->_helperSession = $_helperSession;
    }

    public function beforeExecute(\Magento\Catalog\Controller\Adminhtml\Product\Save $subject){
        if($this->_helper->isRootAdmin()){
            return null;
        }

        $this->performValidate($subject, true);


        $productValues = $subject->getRequest()->getParam('product',[]);
        $id = $subject->getRequest()->getParam('id',false);
        //validate data posted
        $websiteIds = $this->_helper->getWebsiteIds();
        $websiteIds = $this->applyKeys($websiteIds);

        if(false === $id)//new product
        {
            $productValues['website_ids'] = $websiteIds;
            $subject->getRequest()->setPostValue('product',$productValues);
            return null;
        }
        //in edit mode, get stored website ids and put it into post value, avoid to be removed
        $product = $this->getProduct($id);

        $storedWebsiteIds = $product->getWebsiteIds();
        $storedWebsiteIds = $this->applyKeys($storedWebsiteIds);

        $productValues['website_ids'] = $storedWebsiteIds;
        $subject->getRequest()->setPostValue('product',$productValues);

        return null;
    }

    protected function applyKeys($websites)
    {
        $w = [];
        foreach ($websites as $id)
        {
            $w[$id] = $id;
        }
        return $w;
    }
    protected function checkWebsiteEmpty($websiteIds)
    {
        if(!count($websiteIds))
        {
            return true;
        }
        foreach ($websiteIds as $id =>$id1)
        {
            if(0 != $id1)
            {
                return false;
            }
        }
        return true;
    }

    /**
     * This plugin process redirect to correct editing point (website, group, store)
     * after save product to database
     * @param \Magento\Catalog\Controller\Adminhtml\Product\Save $subject
     * @param  \Magento\Framework\Controller\Result\Redirect $result
     * @return void
     */
    public function afterExecute(\Magento\Catalog\Controller\Adminhtml\Product\Save $subject, $result){
        if($this->_helper->isRootAdmin())
        {
            return $result;
        }
        $productId = $this->_helperSession->getData('sau_product_id',true);
        if(!$productId)
        {
            return $result;
        }
        $user = $this->_helper->getUser();
        $storeId = $subject->getRequest()->getParam('store',false);
        if(false !== $storeId || 0 ===  (int)$storeId)
        {
            $storeId = $user->getStoreId();
        }
        $result->setPath('catalog/product/edit',['id'=>$productId,'store'=>$storeId]);
        return $result;
    }
}
