<?php
// phpcs:disable PSR1.Classes.ClassDeclaration

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Validator\Constraint;

use Spryker\Zed\PriceProduct\Business\Validator\Constraint\GreaterThanOrEqualOrEmptyConstraintValidatorTrait\GreaterThanOrEqualOrEmptyConstraintValidatorTraitCommon;

// symfony/validator: <6.0
if (trait_exists('\Symfony\Component\Validator\Constraints\NumberConstraintTrait')) {
    trait GreaterThanOrEqualOrEmptyConstraintValidatorTrait
    {
        use GreaterThanOrEqualOrEmptyConstraintValidatorTraitCommon;

        /**
         * @param mixed $value1
         * @param mixed $value2
         *
         * @return bool
         */
        protected function compareValues($value1, $value2): bool
        {
            return $this->executeCompareValues($value1, $value2);
        }
    }
} else {
    trait GreaterThanOrEqualOrEmptyConstraintValidatorTrait
    {
        use GreaterThanOrEqualOrEmptyConstraintValidatorTraitCommon;

        /**
         * @param mixed $value1
         * @param mixed $value2
         *
         * @return bool
         */
        protected function compareValues(mixed $value1, mixed $value2): bool
        {
            return $this->executeCompareValues($value1, $value2);
        }
    }
}
