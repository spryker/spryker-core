<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Form\Validator;

class EndDateAfterStartDate extends \Zend_Validate_Abstract
{
    const WRONG_ORDER = 'wrongOrder';

    /** @var array */
    protected $_messageTemplates = [
        self::WRONG_ORDER => 'End date must be after start date',
    ];

    /** @var string */
    protected $_startDateContextKey;

    /**
     * @param $startDateContextKey
     * @throws \InvalidArgumentException
     */
    public function __construct($startDateContextKey)
    {
        if (!is_string($startDateContextKey) || trim($startDateContextKey) === '') {
            throw new \InvalidArgumentException('Invalid identifier for start date');
        }

        $this->_startDateContextKey = $startDateContextKey;
    }

    /**
     * @param mixed $value
     * @param array $context
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        $startDate = new \DateTime($context[$this->_startDateContextKey]);
        $endDate = new \DateTime($value);
        if ($startDate->getTimestamp() > $endDate->getTimestamp()) {
            $this->_error(self::WRONG_ORDER);
            return false;
        }
        return true;
    }

}
