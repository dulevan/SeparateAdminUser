<?php
namespace Dulv\SeparateAdminUser\Plugin\All;

class LimitStoreSearchResult
{
    protected $_helper;
    protected $_config;
    protected $_tables;
    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Dulv\SeparateAdminUser\Model\Config $config
    ){
        $this->_helper = $_helper;
        $this->_config = $config;
        $this->_tables = $this->_config->getListOfTables();
    }

    public function beforeLoad(\Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult $subject){
        //$this->_helper->logCheck($subject->getMainTable());

        if(!in_array($subject->getMainTable(), $this->_tables)){
            return null;
        }

        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $availableStoreIds = $this->_helper->getStoreIds();
        if(empty($availableStoreIds)){
            $subject->addFieldToFilter('store_id',-1);
            return null;
        }
        $subject->addFieldToFilter('store_id',['in'=>$availableStoreIds]);
        return null;
    }
}
