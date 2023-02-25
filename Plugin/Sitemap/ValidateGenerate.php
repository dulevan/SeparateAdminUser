<?php

namespace Dulv\SeparateAdminUser\Plugin\Sitemap;

class ValidateGenerate extends ValidateSitemap
{
    protected $_helper;

    public function beforeExecute(\Magento\Sitemap\Controller\Adminhtml\Sitemap\Edit $subject)
    {
        $this->performValidate($subject);
        return null;
    }

}
