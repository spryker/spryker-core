<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Validator\Constraint;

use Symfony\Component\Validator\Constraints\AbstractComparisonValidator;

class GreaterThanOrEqualOrEmptyConstraintValidator extends AbstractComparisonValidator
{
    use GreaterThanOrEqualOrEmptyConstraintValidatorTrait;

    /**
     * @param mixed $value1
     * @param mixed $value2
     *
     * @return bool
     */
    protected function executeCompareValues(mixed $value1, mixed $value2): bool
    {
        return $value1 === null || $value1 >= $value2;
    }
}
