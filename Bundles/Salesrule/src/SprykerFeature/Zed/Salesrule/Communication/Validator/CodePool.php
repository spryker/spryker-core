<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Validator;

use SprykerFeature\Zed\Library\Validator\ValidatorChain;

/**
 * Class CodePool
 * @package SprykerFeature\Zed\Salesrule\Communication\Validator
 */
class CodePool extends ValidatorChain
{
    public function init()
    {
        $this->addValidator(new \Zend_Validate_StringLength(2, 255), 'name');
        $this->addValidator(new \Zend_Validate_StringLength(2, 255), 'prefix');
    }
}
