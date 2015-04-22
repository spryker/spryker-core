<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Validator;

use SprykerFeature\Zed\Library\Validator\ValidatorChain;
use SprykerFeature\Zed\Salesrule\Communication\Form\Validator\Salesrule as SalesRuleValidator;

/**
 * Class SalesRule
 * @package SprykerFeature\Zed\Salesrule\Communication\Validator
 */
class SalesRule extends ValidatorChain
{
    public function init()
    {
        $this->addValidator(new \Zend_Validate_NotEmpty(), 'name');
        $this->addValidator(new \Zend_Validate_NotEmpty(), 'display_name');
        $amountValidators = [
            new \Zend_Validate_NotEmpty(),
            new \Zend_Validate_Int(),
            new SalesRuleValidator()
        ];
        $this->addValidators($amountValidators, 'amount');
        $this->addValidator(new \Zend_Validate_StringLength(2, 255), 'description');
    }
}
