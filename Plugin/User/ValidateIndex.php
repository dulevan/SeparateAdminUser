<?php
namespace Dulv\SeparateAdminUser\Plugin\User;

use \Exception;
use function PHPUnit\Framework\throwException;

class ValidateIndex extends ValidateUser
{
    public function beforeExecute(\Magento\User\Controller\Adminhtml\User\Index $subject)
    {
        if($this->_helper->isStoreUser()){
            $this->_messageManager->addErrorMessage(__("You don't have permission, please contact with you manager if you still want to continue this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('adminhtml/'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        return null;
    }
}
