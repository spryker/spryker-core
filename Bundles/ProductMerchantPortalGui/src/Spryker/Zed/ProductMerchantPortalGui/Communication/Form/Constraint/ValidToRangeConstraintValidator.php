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

class ValidToRangeConstraintValidator extends AbstractConstraintValidator
{
    /**
     * Checks if the Valid to value is not earlier than Valid from.
     *
     * @param string $validTo
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($validTo, Constraint $constraint): void
    {
        if (!$validTo) {
            return;
        }

        if (!$constraint instanceof ValidToRangeConstraint) {
            throw new UnexpectedTypeException($constraint, ValidToRangeConstraint::class);
        }

        /** @var \Symfony\Component\Form\FormInterface $form */
        $form = $this->context->getObject();
        /** @var \Symfony\Component\Form\FormInterface $parentForm */
        $parentForm = $form->getParent();
        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer|array $formData */
        $formData = $parentForm->getData();
        $validFrom = is_array($formData) ? $formData[ProductConcreteTransfer::VALID_FROM] : $formData->getValidFrom();

        if (!$validFrom) {
            return;
        }

        $validTo = new DateTime($validTo);
        $validFrom = new DateTime($validFrom);

        if ($validTo < $validFrom) {
            $this->context->addViolation('The second date cannot be earlier than the first one.');
        }
    }
}
