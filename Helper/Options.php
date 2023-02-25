<?php
namespace Dulv\SeparateAdminUser\Helper;

use Magento\Framework\App\Helper\Context;

class Options extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_storeOptions = null;
    protected $_storeOptionsMultiple = null;

    protected $_websiteStoreOptions = null;
    protected $_helper;

    public function __construct(Context $context,
        \Dulv\SeparateAdminUser\Helper\Data $helper
    ){
        $this->_helper = $helper;
        parent::__construct($context);
    }

    /**
     * @return [
     *      0 => [
     *          'label' => Website Name,
     *          'value' => [
     *              'label' => Group Name,
     *              'value' => [
     *                  0 => [
     *                      'label' => Store Name 1,
     *                      'value' => Store Id 1
     *                  ],
     *                  1 => [
     *                      'label' => Store Name 2,
     *                      'value' => Store Id 2
     *                  ]
     *              ]
     *          ]
     *      ]
     * ]
     */
    public function getStoreOptions()
    {
        if(!is_null($this->_storeOptions))
        {
            return $this->_storeOptions;
        }
        $this->_storeOptions = [];

        list($websiteCollection, $groupCollection, $storeCollection) = $this->_helper->getCollections();

        foreach ($websiteCollection as $website)
        {
            $groups = [];
            foreach ($groupCollection as $group) {
                if ($group->getWebsiteId() === $website->getId()) {
                    $stores = [];
                    foreach ($storeCollection as $store) {
                            if ($store->getGroupId() === $group->getId()) {
                                $stores[] = [
                                    'label' => str_repeat(' ', 8) . $this->_helper->sanitizeName($store->getName()),
                                    'value' => $store->getId(),
                                ];
                            }
                    }
                    if (!empty($stores)) {
                        $groups[] = [
                            'label' => str_repeat(' ', 4) . $this->_helper->sanitizeName($group->getName()),
                            'value' => array_values($stores),
                        ];
                    }
                }
            }
            if (!empty($groups)) {
                $this->_storeOptions[] = [
                    'label' => $this->_helper->sanitizeName($website->getName()),
                    'value' => array_values($groups),
                ];
            }
        }
        return $this->_storeOptions;
    }

    /**
     * @return [
     *      0 = [
     *          'label' => Website Name,
     *          'value' => [],
     *          '__disableTmpl' => true
     *      ],
     *      1 => [
     *          'label' => Group Name 1,
     *          'value' => [
     *              0 => ['label' => Store Name 1, 'value' => Store Id 1],
     *              1 => ['label' => Store Name 2, 'value' => Store Id 2]
     *          ],
     *          '__disableTmpl' => true
     *      ]
     * ]
     */
    public function getStoreOptionsForMultipleSelect()
    {
        if(!is_null($this->_storeOptionsMultiple))
        {
            return $this->_storeOptionsMultiple;
        }
        $this->_storeOptionsMultiple = [];

        list($websiteCollection, $groupCollection, $storeCollection) = $this->_helper->getCollections();

        foreach ($websiteCollection as $website)
        {
            $this->_storeOptionsMultiple[] = ['label'=>$this->_helper->sanitizeName($website->getName()),'value'=>[]];
            foreach ($groupCollection as $group) {
                if ($group->getWebsiteId() === $website->getId() ) {
                    $stores = [];
                    foreach ($storeCollection as $store) {
                        if ($store->getGroupId() === $group->getId()) {
                            $stores[] = [
                                'label' => str_repeat('&nbsp;', 8) . $this->_helper->sanitizeName($store->getName()),
                                'value' => $store->getId(),
                            ];
                        }
                    }
                    if (!empty($stores)) {
                        $this->_storeOptionsMultiple[] = [
                            'label' => str_repeat('&nbsp;', 4) . $this->_helper->sanitizeName($group->getName()),
                            'value' => array_values($stores),
                        ];
                    }
                }
            }
        }
        array_walk($this->_storeOptionsMultiple,function (&$item){
            $item['__disableTmpl'] = true;
        });

        return $this->_storeOptionsMultiple;
    }
    /**
     * @return [
     *      0 = [
     *          'label' => Website Name,
     *          'value' => [],
     *          '__disableTmpl' => true
     *      ],
     *      1 => [
     *          'label' => Group Name 1,
     *          'value' => [
     *              0 => ['label' => Store Name 1, 'value' => website_id:store_id],
     *              1 => ['label' => Store Name 2, 'value' => website_id:store_id]
     *          ],
     *          '__disableTmpl' => true
     *      ]
     * ]
     */
    public function getWebsiteStoreOptions()
    {
        if(!is_null($this->_websiteStoreOptions))
        {
            return $this->_websiteStoreOptions;
        }
        $this->_websiteStoreOptions = [];

        list($websiteCollection, $groupCollection, $storeCollection) = $this->_helper->getCollections();

        foreach ($websiteCollection as $website)
        {
            $this->_websiteStoreOptions[] = ['label'=>$this->_helper->sanitizeName($website->getName()),'value'=>[]];
            foreach ($groupCollection as $group) {
                if ($group->getWebsiteId() === $website->getId() ) {
                    $stores = [];

                    foreach ($storeCollection as $store) {
                        if ($store->getGroupId() === $group->getId()) {
                            $stores[] = [
                                'label' => str_repeat('&nbsp;', 8) . $this->_helper->sanitizeName($store->getName()),
                                'value' => $website->getId().':'.$store->getId(),
                            ];
                        }
                    }
                    if (!empty($stores)) {
                        $this->_websiteStoreOptions[] = [
                            'label' => str_repeat('&nbsp;', 4) . $this->_helper->sanitizeName($group->getName()),
                            'value' => array_values($stores),
                        ];
                    }
                }
            }
        }
        array_walk($this->_websiteStoreOptions,function (&$item){
            $item['__disableTmpl'] = true;
        });

        return $this->_websiteStoreOptions;
    }

    /**
     * @return [
     *      0 => ['label' => Website Name 1, 'value' => Website Id 1],
     *      1 => ['label' => Website Name 2, 'value' => Website Id 2]
     * ]
     */
    public function getWebsiteOption()
    {
        list($websiteCollection, $groupCollection, $storeCollection) = $this->_helper->getCollections();
        $options = [];
        foreach ($websiteCollection as $website)
        {
            $options[] = ['label' => $website->getName(),'value'=>$website->getId()];
        }
        return $options;
    }

    public function getStoresStruct(){

        list($websiteCollection, $groupCollection, $storeCollection) = $this->_helper->getCollections();
        return $this->_getStoreStruct($websiteCollection, $groupCollection, $storeCollection);
    }

    protected function _getStoreStruct($websiteCollection, $groupCollection, $storeCollection){
        $struct = [];
        foreach ($websiteCollection as $website)
        {
            $websiteId = $website->getId();
            $struct[$websiteId] = [
                'label'=>$this->_helper->sanitizeName($website->getName()),
                'value' => $websiteId
            ];
            foreach ($groupCollection as $group)
            {
                if($group->getWebsiteId() != $websiteId)
                {
                    continue;
                }
                $groupId = $group->getId();
                $struct[$websiteId]['children'][$groupId] = [
                    'label' => $this->_helper->sanitizeName($group->getName()),
                    'value' => $groupId
                ];
                foreach ($storeCollection as $store)
                {
                    if($store->getGroupId() != $groupId)
                    {
                        continue;
                    }
                    $storeId = $store->getId();
                    $struct[$websiteId]['children'][$groupId]['children'][$storeId] = [
                        'label'=>$this->_helper->sanitizeName($store->getName()),
                        'value' => $storeId
                    ];
                }
            }
        }
        return $struct;
    }
}
