<?php

namespace Dulv\SeparateAdminUser\Plugin\UrlRewrite;

class ValidateDelete extends ValidateUrlRewrite
{
    public function beforeExecute(\Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite\Delete $subject){
        parent::performValidate($subject);
        return null;
    }
}
