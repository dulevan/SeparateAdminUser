<?php

namespace Dulv\SeparateAdminUser\Plugin\Search\Query;

class ValidateEdit extends ValidateSearchQuery
{
    public function beforeExecute(
        \Magento\Search\Controller\Adminhtml\Term\Edit $subject
    ){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $id = $subject->getRequest()->getParam('id',null);
        if(is_null($id)){ // new action
            return null;
        }

        $this->performValidate($subject, $id);
        return null;
    }
}
