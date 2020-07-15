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

class ValidFromRangeConstraintValidator extends AbstractConstraintValidator
{
    /**
     * Checks if the Valid from value is earlier than Valid to.
     *
     * @param string $validFrom
     * @param \Symfony\Component\Validator\Constraint $validFromRangeConstraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($validFrom, Constraint $validFromRangeConstraint): void
    {
        if (!$validFrom) {
            return;
        }

        if (!$validFromRangeConstraint instanceof ValidFromRangeConstraint) {
            throw new UnexpectedTypeException($validFromRangeConstraint, ValidFromRangeConstraint::class);
        }

        /** @var \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer */
        $productOfferTransfer = $this->context->getRoot()->getData();
        $productOfferValidityTransfer = $productOfferTransfer->getProductOfferValidity();

        if (!$productOfferValidityTransfer || !$productOfferValidityTransfer->getValidTo()) {
            return;
        }

        $validTo = new DateTime($productOfferValidityTransfer->getValidTo());
        $validFrom = new DateTime($validFrom);

        if ($validFrom > $validTo) {
            $this->context->addViolation('The first date cannot be later than the second one.');
        }

        if ($validFrom === $validTo) {
            $this->context->addViolation('The first date is the same as the second one.');
        }
    }
}
