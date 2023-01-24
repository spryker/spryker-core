<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Validator\Constraint\GreaterThanOrEqualOrEmptyConstraintValidatorTrait;

trait GreaterThanOrEqualOrEmptyConstraintValidatorTraitCommon
{
    /**
     * @param mixed $value1
     * @param mixed $value2
     *
     * @return bool
     */
    abstract protected function executeCompareValues(mixed $value1, mixed $value2): bool;
}
