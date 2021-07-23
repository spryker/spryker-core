<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint;

use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 */
class UniqueConcreteSkuConstraintValidator extends AbstractConstraintValidator
{
    /**
     * @param string $sku
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($sku, Constraint $constraint): void
    {
        if (!$sku) {
            return;
        }

        if (!$constraint instanceof UniqueConcreteSkuConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueConcreteSkuConstraint::class);
        }

        if ($this->getFactory()->getProductFacade()->hasProductConcrete($sku)) {
            $this->context->buildViolation($constraint->getMessage())->addViolation();
        }
    }
}
