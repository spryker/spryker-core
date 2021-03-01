<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint;

use DateTime;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidFromRangeConstraintValidator extends AbstractConstraintValidator
{
    /**
     * Checks if the Valid from value is earlier than Valid to.
     *
     * @param string $validFrom
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($validFrom, Constraint $constraint): void
    {
        if (!$validFrom) {
            return;
        }

        if (!$constraint instanceof ValidFromRangeConstraint) {
            throw new UnexpectedTypeException($constraint, ValidFromRangeConstraint::class);
        }

        $formData = $this->context->getRoot()->getData();
        $validTo = $formData[ProductConcreteTransfer::VALID_TO];

        if (!$validTo) {
            return;
        }

        $validTo = new DateTime($validTo);
        $validFrom = new DateTime($validFrom);

        if ($validFrom > $validTo) {
            $this->context->addViolation('The first date cannot be later than the second one.');
        }

        if ($validFrom->format('Y-m-d') === $validTo->format('Y-m-d')) {
            $this->context->addViolation('The first date is the same as the second one.');
        }
    }
}
