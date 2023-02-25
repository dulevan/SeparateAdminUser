<?php

namespace Dulv\SeparateAdminUser\Plugin\Search\Synonyms;

class ValidateEdit extends ValidateSynonyms
{

    public function beforeExecute(\Magento\Search\Controller\Adminhtml\Synonyms\Edit $subject){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $groupId = $subject->getRequest()->getParam('group_id',null);
        if(is_null($groupId))
        {
            return null;
        }
        $this->performValidate($subject, $groupId);
        return null;
    }
}
