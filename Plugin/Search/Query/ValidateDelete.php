<?php

namespace Dulv\SeparateAdminUser\Plugin\Search\Query;

class ValidateDelete extends ValidateSearchQuery
{
    public function beforeExecute(
        \Magento\Search\Controller\Adminhtml\Term\Delete $subject
    ){
        $this->performValidate($subject);
        return null;
    }
}
