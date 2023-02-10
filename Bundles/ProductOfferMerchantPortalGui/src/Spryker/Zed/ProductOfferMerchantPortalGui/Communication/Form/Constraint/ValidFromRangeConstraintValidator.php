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
class ValidFromRangeConstraintValidator extends AbstractConstraintValidator
{
    /**
     * Checks if the Valid from value is earlier than Valid to.
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

        if (!$constraint instanceof ValidFromRangeConstraint) {
            throw new UnexpectedTypeException($constraint, ValidFromRangeConstraint::class);
        }

        /** @var \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer */
        $productOfferTransfer = $this->context->getRoot()->getData();
        $productOfferValidityTransfer = $productOfferTransfer->getProductOfferValidity();

        if (!$productOfferValidityTransfer) {
            return;
        }

        $validTo = $productOfferValidityTransfer->getValidTo();

        if (!$validTo) {
            return;
        }

        $validTo = new DateTime($validTo);
        $value = new DateTime($value);

        if ($value > $validTo) {
            $this->context->addViolation('The first date cannot be later than the second one.');
        }

        if ($value == $validTo) {
            $this->context->addViolation('The first date is the same as the second one.');
        }
    }
}
