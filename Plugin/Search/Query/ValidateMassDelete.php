<?php

namespace Dulv\SeparateAdminUser\Plugin\Search\Query;

class ValidateMassDelete
{
    protected $_helper;

    protected $_queryCollectionFactory;
    protected $_queryModelFactory;
    protected $_queryResourceModel;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Magento\Search\Model\ResourceModel\Query\CollectionFactory $_collectionFactory,
        \Magento\Search\Model\QueryFactory $_queryModelFactory,
        \Magento\Search\Model\ResourceModel\QueryFactory $_queryResourceModelFactory
    ){
        $this->_helper = $_helper;
        $this->_queryCollectionFactory = $_collectionFactory;
        $this->_queryModelFactory = $_queryModelFactory;
        $this->_queryResourceModel = $_queryResourceModelFactory->create();
    }
    public function beforeExecute(\Magento\Search\Controller\Adminhtml\Term\MassDelete $subject){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $storeIds = $this->_helper->getStoreIds();
        if(empty($storeIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('search/term/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        $excluded = $subject->getRequest()->getParam('excluded',null);
        $ids = [];
        if(is_null($excluded)){
            $ids = $subject->getRequest()->getParam('selected',[]);
        }

        if(is_null($excluded) && count($ids)){
            $collection = $this->_queryCollectionFactory->create();
            $collection->addFieldToFilter('query_id',['in'=>$ids]);
            foreach ($collection as $item){
                $storeId = $item->getStoreId();
                if($storeId != 0 && !in_array($storeId, $storeIds) )
                {
                    $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
                    $subject->getResponse()->setRedirect($subject->getUrl('search/synonyms/index'));
                    $subject->getResponse()->sendResponse();
                    exit();
                }
            }
        }
        return null;
    }
}
