<?php
namespace Dulv\SeparateAdminUser\Plugin\User;

use \Exception;
use Magento\User\Model\User;
use Magento\User\Model\UserFactory;
use function PHPUnit\Framework\throwException;

class ValidateEdit extends ValidateUser
{
    public function beforeExecute(\Magento\User\Controller\Adminhtml\User\Edit $subject)
    {
        $this->performValidateByLevel($subject);
        return null;
    }
}
