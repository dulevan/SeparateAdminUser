<?php

namespace Dulv\SeparateAdminUser\Model;

use Magento\Framework\Session\SessionManager;

class Session extends SessionManager
{
    const VALID_FILTER_VALUE = 1;

    public function setValideFilter($name){
        $this->setData($name, self::VALID_FILTER_VALUE);
        return $this;
    }

    public function isValidStoreFilter($name, $clear=false){
        $valid = $this->getData($name);
        if(!is_null($valid)){
            $valid = intval($valid);
            if(self::VALID_FILTER_VALUE == $valid) {
                if($clear){
                    $this->unsData($name);
                }
                return true;
            }
        }
        if($clear){
            $this->unsData($name);
        }
        return false;
    }
    public function clearValidFilter($name){
        $this->unsData($name);
        return $this;
    }
}
