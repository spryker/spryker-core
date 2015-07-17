<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Validator;

class DateTimeFormat extends \Zend_Validate_Abstract
{

    const WRONG_FORMAT = 'wrongFormat';

    /**
     * @var array
     */
    protected $_messageTemplates = [
        self::WRONG_FORMAT => 'The format needs to be YYYY-MM-DD HH:MM',
    ];

    /**
     * @param mixed $value
     * @param array $context
     *
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        if (!preg_match('/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})$/', $value)) {
            $this->_error(self::WRONG_FORMAT);

            return false;
        }

        return true;
    }

}
