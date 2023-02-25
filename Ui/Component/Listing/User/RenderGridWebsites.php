<?php
namespace Dulv\SeparateAdminUser\Ui\Component\Listing\User;

use Magento\Backend\Block\Context;

class RenderGridWebsites extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_systemStore;
    protected $_helper;
    public function __construct(Context $context,
                                \Magento\Store\Model\System\Store $systemStore,
                                \Dulv\SeparateAdminUser\Helper\Data $helper,
                                array $data = [])
    {
        $this->_systemStore = $systemStore;
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        $websiteCollection = $this->_systemStore->getWebsiteCollection();
        $groupCollection = $this->_systemStore->getGroupCollection();
        $storeCollection = $this->_systemStore->getStoreCollection();

        $website_id = $row->getData('website_id');
        if(is_null($website_id) || (!is_null($website_id) && $website_id==0)){
            return '';
        }
        $group_id = $row->getData('group_id');
        $store_id = $row->getData('store_id');

        $text = '';
        if(!isset($websiteCollection[$website_id])){
            return __('Root User');
        }
        $text = $text.'<span style="display:block">'.$this->_helper->sanitizeName($websiteCollection[$website_id]->getName()).'</span>';
        if(is_null($group_id) || (!is_null($text) && !isset($groupCollection[$group_id])) ){
            return $text;
        }
        $text = $text.'<span style="display:block;margin-left:15px;">'.$this->_helper->sanitizeName($groupCollection[$group_id]->getName()).'</span>';

        if(is_null($store_id) || (!is_null($store_id) && !isset($storeCollection[$store_id]))){
            return $text;
        }
        $text = $text.'<span style="display:block;margin-left: 30px">'.$this->_helper->sanitizeName($storeCollection[$store_id]->getName()).'</span>';
        $text = $text;
        return $text;
    }
}
