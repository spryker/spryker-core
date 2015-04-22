<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Form\Validator;

class Salesrule extends \Zend_Validate_Abstract
{
    const WRONG_PERCENTAGE = 'wrongPercentage';
    const WRONG_NUMBER = 'wrongNumber';

    const KEY_ACTION = 'action';
    const IDENTIFIER_PERCENT = 'percent';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::WRONG_PERCENTAGE => "Percentage cannot be less than 0 or greater than 100",
        self::WRONG_NUMBER     => "Amount cannot be less then 1",
    );

    /**
     * @param mixed $value
     * @param array $context
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        if (stristr($context[self::KEY_ACTION], self::IDENTIFIER_PERCENT)) {
            if ($value < 1 || $value > 100) {
                $this->_error(self::WRONG_PERCENTAGE);
                return false;
            }
        } else {
            if ($value < 1) {
                $this->_error(self::WRONG_NUMBER);
                return false;
            }
        }
        return true;
    }
}
