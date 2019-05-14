<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\Validator\Constraints;

use DateTime;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqualValidator;

/**
 * Validates date type values are greater than or equal to the previous (>=).
 */
class GreaterThanOrEqualDateValidator extends GreaterThanOrEqualValidator
{
    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param \Spryker\Zed\ShipmentGui\Communication\Form\Validator\Constraints\GreaterThanOrEqualDate $constraint The constraint for the validation
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        $valueAsDateTime = DateTime::createFromFormat($constraint->format, $value);

        parent::validate($valueAsDateTime, $constraint);
    }
}
