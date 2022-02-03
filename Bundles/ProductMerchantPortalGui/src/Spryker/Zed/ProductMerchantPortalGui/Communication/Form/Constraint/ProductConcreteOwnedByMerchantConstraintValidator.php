<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint;

use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ProductConcreteOwnedByMerchantConstraintValidator extends AbstractConstraintValidator
{
    /**
     * Checks if concrete product owned by merchant.
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer|null $value
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductConcreteOwnedByMerchantConstraint $constraint
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

        if (!$constraint instanceof ProductConcreteOwnedByMerchantConstraint) {
            throw new UnexpectedTypeException($constraint, ProductConcreteOwnedByMerchantConstraint::class);
        }

        $merchantTransfer = $constraint->getMerchantUserFacade()->getCurrentMerchantUser()->getMerchantOrFail();
        $isProductConcreteOwnedByMerchant = $constraint->getMerchantProductFacade()->isProductConcreteOwnedByMerchant(
            $value,
            $merchantTransfer,
        );

        if (!$isProductConcreteOwnedByMerchant) {
            $this->context->addViolation($constraint->getMessage());
        }
    }
}
