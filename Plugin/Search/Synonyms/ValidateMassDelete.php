<?php

namespace Dulv\SeparateAdminUser\Plugin\Search\Synonyms;

class ValidateMassDelete
{
    protected $_helper;

    protected $_synonymCollectionFactory;
    protected $_synGroupRepository;

    public function __construct(
        \Dulv\SeparateAdminUser\Helper\Data $_helper,
        \Magento\Search\Model\ResourceModel\SynonymGroup\CollectionFactory $collectionFactory,
        \Magento\Search\Api\SynonymGroupRepositoryInterface $synGroupRepository
    ){
        $this->_helper = $_helper;
        $this->_synonymCollectionFactory = $collectionFactory;
        $this->_synGroupRepository = $synGroupRepository;
    }
    public function beforeExecute(\Magento\Search\Controller\Adminhtml\Synonyms\MassDelete $subject){
        if($this->_helper->isRootAdmin())
        {
            return null;
        }
        $storeIds = $this->_helper->getStoreIds();
        if(empty($storeIds))
        {
            $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
            $subject->getResponse()->setRedirect($subject->getUrl('search/synonyms/index'));
            $subject->getResponse()->sendResponse();
            exit();
        }
        $websiteIds = $this->_helper->getWebsiteIds();
        $excluded = $subject->getRequest()->getParam('excluded',null);
        $ids = [];
        if(is_null($excluded)){
            $ids = $subject->getRequest()->getParam('selected',[]);
        }

        if(is_null($excluded) && count($ids)){
            $collection = $this->_synonymCollectionFactory->create();
            $collection->addFieldToFilter('group_id',['in'=>$ids]);
            foreach ($collection as $item){
                if(!in_array($item->getWebsiteId(), $websiteIds)){
                    $this->_messageManager->addErrorMessage(__("You don't have permission to perform this action"));
                    $subject->getResponse()->setRedirect($subject->getUrl('search/synonyms/index'));
                    $subject->getResponse()->sendResponse();
                    exit();
                }
                $storeId = $item->getStoreId();
                if($storeId != 0 && !in_array($storeId, $storeIds) ){
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
