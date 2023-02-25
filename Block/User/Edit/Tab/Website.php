<?php
namespace Dulv\SeparateAdminUser\Block\User\Edit\Tab;

use Magento\Store\Model\ResourceModel\Store\CollectionFactory;

class Website
{
    /**
     * @var \Magento\Store\Model\ResourceModel\Website\CollectionFactory
     */
    protected $websiteCollectionFactory;
    protected $storeCollectionFactory;

    protected $helper;
    protected $userhelper;
    protected $optionsHelper;

    protected $_coreRegistry;
    public function __construct(
        \Magento\Store\Model\ResourceModel\Website\CollectionFactory $collectionFactory,
        \Magento\Store\Model\ResourceModel\Store\CollectionFactory $storeCollectionFactory,
        \Dulv\SeparateAdminUser\Helper\Data $helper,
        \Dulv\SeparateAdminUser\Helper\User $userhelper,
        \Dulv\SeparateAdminUser\Helper\Options $optionsHelper,
        \Magento\Framework\Registry $registry
    ){
        $this->websiteCollectionFactory = $collectionFactory;
        $this->helper = $helper;
        $this->userhelper = $userhelper;
        $this->optionsHelper = $optionsHelper;
        $this->storeCollectionFactory = $storeCollectionFactory;
        $this->_coreRegistry = $registry;
    }
    public function aroundGetFormHtml(
        \Magento\User\Block\User\Edit\Tab\Main $subject,
        \Closure $proceed)
    {
        $form = $subject->getForm();
        if (is_object($form))
        {
            $fieldset = $form->addFieldset('fieldset_website_id', ['legend' => __('Website')]);
            $website = $fieldset->addField(
                'website_id',
                'select',
                [
                    'name' => 'website_id',
                    'label' => __('Website'),
                    'id' => 'website_id',
                    'title' => __('Website'),
                    'required' => false,
                    'values' =>$this->userhelper->getStoreOptionsArray(),
                    'class' => 'select'
                ]
            );
            /** @var $model \Magento\User\Model\User */
            $model = $this->_coreRegistry->registry('permissions_user');
            $values = $model->getData();

            if(!is_null($values) && is_array($values)) {
                $website_id = isset($values['website_id'])?$values['website_id']:null;
                $store_id = isset($values['store_id'])?$values['store_id']:null;
                $group_id = isset($values['group_id'])?$values['group_id']:null;
                $val = '';
                if (!is_null($website_id)) {
                    $val = $website_id;
                }
                if (!is_null($group_id)) {
                    $val = $val . '.' . $group_id;
                }
                if (!is_null($store_id)) {
                    $val = $val . '.' . $store_id;
                }
                unset($values['password']);
                unset($values[\Magento\User\Block\User\Edit\Tab\Main::CURRENT_USER_PASSWORD_FIELD]);
                $values['website_id'] = $val;
                $form->setValues($values);
            }
            $subject->setForm($form);
        }

        return $proceed();
    }
}
