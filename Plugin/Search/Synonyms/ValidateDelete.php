<?php

namespace Dulv\SeparateAdminUser\Plugin\Search\Synonyms;

class ValidateDelete extends ValidateSynonyms
{

    public function beforeExecute(\Magento\Search\Controller\Adminhtml\Synonyms\Delete $subject){
        $this->performValidate($subject);
        return null;
    }
}
