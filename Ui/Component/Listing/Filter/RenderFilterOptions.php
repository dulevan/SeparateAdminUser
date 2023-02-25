<?php
namespace Dulv\SeparateAdminUser\Ui\Component\Listing\Filter;

use Magento\Framework\Data\OptionSourceInterface;

class RenderFilterOptions implements OptionSourceInterface
{
    protected $_userHelper;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\User $userHepler
    ){
        $this->_userHelper = $userHepler;
    }
    public function getOptions()
    {
        return $this->_userHelper->getAllOptionsArray();
    }
    public function getOptionsArray()
    {
        return $this->_userHelper->getAllOptionsArray();
    }

    public function toOptionArray()
    {
        return $this->_userHelper->getAllOptionsArray();
    }
}
