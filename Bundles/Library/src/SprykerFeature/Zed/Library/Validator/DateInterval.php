<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Validator;

class DateInterval extends \Zend_Validate_Abstract
{

    const WRONG_FORMAT = 'wrongFormat';

    /**
     * @var array
     */
    protected $_messageTemplates = [
        self::WRONG_FORMAT => 'The date interval is not a valid DateInterval string',
    ];

    /**
     * @param string $dateString
     *
     * @return bool
     */
    public function isValid($dateString)
    {
        $dateInterval = \DateInterval::createFromDateString($dateString);
        if (count(array_filter((array) $dateInterval)) === 0) {
            $this->_error(self::WRONG_FORMAT);

            return false;
        }

        return true;
    }

}
