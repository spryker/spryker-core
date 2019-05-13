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
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        $valueAsDateTime = new DateTime($value);

        parent::validate($valueAsDateTime, $constraint);
    }
}
