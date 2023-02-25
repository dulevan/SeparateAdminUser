<?php

namespace Dulv\SeparateAdminUser\Plugin\Sitemap;

class ValidateDelete extends ValidateSitemap
{
    protected $_helper;

    public function beforeExecute(\Magento\Sitemap\Controller\Adminhtml\Sitemap\Delete $subject)
    {
        $this->performValidate($subject);
        return null;
    }

}
