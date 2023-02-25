<?php

namespace Dulv\SeparateAdminUser\Plugin\UrlRewrite;

class ValidateEdit extends ValidateUrlRewrite
{
    public function beforeExecute(\Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite\Edit $subject)
    {
        parent::performValidate($subject);
        return null;
    }
}
