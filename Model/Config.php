<?php

namespace Dulv\SeparateAdminUser\Model;

class Config
{
    protected $scopeConfig;

    protected $tables = null;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }
    public function getListOfTables(){
        if(is_null($this->tables)) {
            $value = $this->scopeConfig->getValue(
                'separateadminuser/tables/table_name',
                \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITES
            );
            $value = trim($value);
            if (empty($value)) {
                $this->tables = [];
                return $this->tables;
            }
            $this->tables = [];
            $parts = explode(',', $value);
            foreach ($parts as $part){
                $this->tables[] = trim($part);
            }
        }
        return $this->tables;
    }
}
