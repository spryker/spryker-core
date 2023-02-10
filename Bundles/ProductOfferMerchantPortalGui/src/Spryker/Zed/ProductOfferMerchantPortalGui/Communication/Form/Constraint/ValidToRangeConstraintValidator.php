<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Constraint;

use DateTime;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 */
class ValidToRangeConstraintValidator extends AbstractConstraintValidator
{
    /**
     * Checks if the Valid to value is not earlier than Valid from.
     *
     * @param mixed|string $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value) {
            return;
        }

        if (!$constraint instanceof ValidToRangeConstraint) {
            throw new UnexpectedTypeException($constraint, ValidToRangeConstraint::class);
        }

        /** @var \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer */
        $productOfferTransfer = $this->context->getRoot()->getData();
        $productOfferValidityTransfer = $productOfferTransfer->getProductOfferValidity();

        if (!$productOfferValidityTransfer) {
            return;
        }

        $validFrom = $productOfferValidityTransfer->getValidFrom();

        if (!$validFrom) {
            return;
        }

        $value = new DateTime($value);
        $validFrom = new DateTime($validFrom);

        if ($value < $validFrom) {
            $this->context->addViolation('The second date cannot be earlier than the first one.');
        }
    }
}
