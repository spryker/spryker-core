<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Form\Validator;

class Code extends \Zend_Validate_Abstract
{
    const CODE_ALREADY_EXISTS = 'code_already_exists';
    const KEY_CODE = 'code';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::CODE_ALREADY_EXISTS => "This code already exists",
    );

    /**
     * @param string $value
     * @param array $context
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        $code = $context[self::KEY_CODE];

        if (\SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodeQuery::create()->findOneByCode($code)) {
            $this->_error(self::CODE_ALREADY_EXISTS);
            return false;
        } else {
            return true;
        }
    }
}
