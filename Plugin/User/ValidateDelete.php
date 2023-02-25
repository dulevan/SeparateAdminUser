<?php
namespace Dulv\SeparateAdminUser\Plugin\User;

use \Exception;
use Magento\User\Model\User;
use Magento\User\Model\UserFactory;
use function PHPUnit\Framework\throwException;

class ValidateDelete extends ValidateUser
{

    public function beforeExecute(\Magento\User\Controller\Adminhtml\User\Delete $subject)
    {
        $this->performValidateByLevel($subject);
        return null;
    }
}
