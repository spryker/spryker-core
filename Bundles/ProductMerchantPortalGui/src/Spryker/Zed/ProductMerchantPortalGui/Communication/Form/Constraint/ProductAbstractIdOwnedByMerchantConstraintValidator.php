<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 */
class ProductAbstractIdOwnedByMerchantConstraintValidator extends AbstractConstraintValidator
{
    /**
     * Checks if abstract product with provided ID owned by merchant.
     *
     * @param mixed|int $value ID product abstract
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductAbstractIdOwnedByMerchantConstraint) {
            throw new UnexpectedTypeException($constraint, ProductAbstractIdOwnedByMerchantConstraint::class);
        }

        $merchantTransfer = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser()->getMerchantOrFail();
        $isProductAbstractOwnedByMerchant = $this->getFactory()->getMerchantProductFacade()->isProductAbstractOwnedByMerchant(
            (new ProductAbstractTransfer())->setIdProductAbstract($value),
            $merchantTransfer,
        );

        if (!$isProductAbstractOwnedByMerchant) {
            $this->context->addViolation($constraint->getMessage());
        }
    }
}
