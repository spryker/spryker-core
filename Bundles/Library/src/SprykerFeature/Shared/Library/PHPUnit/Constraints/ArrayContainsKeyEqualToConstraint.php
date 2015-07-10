<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\PHPUnit\Constraints;

class ArrayContainsKeyEqualToConstraint extends \PHPUnit_Framework_Constraint
{

    /**
     * @var string
     */
    protected $key;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param string $key
     * @param mixed $value
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @param array $other
     * @param string $description
     * @param bool $returnResult
     *
     * @return bool
     */
    public function evaluate($other, $description = '', $returnResult = false)
    {
        if (!is_array($other)) {
            if ($returnResult) {
                return false;
            }

            throw new \PHPUnit_Framework_ExpectationFailedException(
                trim($description . "\n" . 'Value is not an array')
            );
        }

        if (!isset($other[$this->key])) {
            if ($returnResult) {
                return false;
            }

            throw new \PHPUnit_Framework_ExpectationFailedException(
                trim($description . "\n" . 'Array does not contain the expected key ' . $this->key)
            );
        }

        $comparatorFactory = \PHPUnit_Framework_ComparatorFactory::getDefaultInstance();

        try {
            $comparator = $comparatorFactory->getComparatorFor(
                $other[$this->key],
                $this->value
            );

            $comparator->assertEquals(
                $this->value,
                $other[$this->key]
            );
        } catch (\PHPUnit_Framework_ComparisonFailure $f) {
            if ($returnResult) {
                return false;
            }

            throw new \PHPUnit_Framework_ExpectationFailedException(
                trim($description . "\n" . $f->getMessage()),
                $f
            );
        }

        return true;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        if (is_string($this->value)) {
            return sprintf('Key %s is equal to %s', $this->key, $this->value);
        } else {
            return sprintf(
                'key %s is equal to %s',
                $this->key,
                \PHPUnit_Util_Type::export($this->value)
            );
        }
    }

}
