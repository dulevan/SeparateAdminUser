<?php
namespace Dulv\SeparateAdminUser\Plugin\Catalog\Product;

class ValidateEdit extends ValidateProduct
{

    public function beforeExecute(\Magento\Catalog\Controller\Adminhtml\Product\Edit $subject){

        if($this->_helper->isRootAdmin()){
            return null;
        }
        return $this->performValidate($subject, true);
    }

}
