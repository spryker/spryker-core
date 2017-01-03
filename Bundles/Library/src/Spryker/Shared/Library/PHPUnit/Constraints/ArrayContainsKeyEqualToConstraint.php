<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\PHPUnit\Constraints;

use PHPUnit_Framework_Constraint;
use PHPUnit_Framework_ExpectationFailedException;
use PHPUnit_Util_Type;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Comparator\Factory;

class ArrayContainsKeyEqualToConstraint extends PHPUnit_Framework_Constraint
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
     * @throws \PHPUnit_Framework_ExpectationFailedException
     *
     * @return bool
     */
    public function evaluate($other, $description = '', $returnResult = false)
    {
        if (!is_array($other)) {
            if ($returnResult) {
                return false;
            }

            throw new PHPUnit_Framework_ExpectationFailedException(
                trim($description . "\n" . 'Value is not an array')
            );
        }

        if (!isset($other[$this->key])) {
            if ($returnResult) {
                return false;
            }

            throw new PHPUnit_Framework_ExpectationFailedException(
                trim($description . "\n" . 'Array does not contain the expected key ' . $this->key)
            );
        }

        $comparatorFactory = Factory::getInstance();

        try {
            $comparator = $comparatorFactory->getComparatorFor(
                $other[$this->key],
                $this->value
            );

            $comparator->assertEquals(
                $this->value,
                $other[$this->key]
            );
        } catch (ComparisonFailure $f) {
            if ($returnResult) {
                return false;
            }

            throw new PHPUnit_Framework_ExpectationFailedException(
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
                PHPUnit_Util_Type::export($this->value)
            );
        }
    }

}
