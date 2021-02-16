<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business\Validator\Constraint;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ProductAbstractBelongsToMerchantConstraintValidator extends AbstractConstraintValidator
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof MerchantProductTransfer) {
            throw new UnexpectedTypeException($value, MerchantProductTransfer::class);
        }

        if (!$constraint instanceof ProductAbstractBelongsToMerchantConstraint) {
            throw new UnexpectedTypeException($constraint, ProductAbstractBelongsToMerchantConstraint::class);
        }

        /** @var \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer */
        $productAbstractTransfer = $value->requireProductAbstract()->getProductAbstract();

        /** @var int $idMerchant */
        $idMerchant = $value->requireIdMerchant()->getIdMerchant();
        /** @var int $idProductAbstract */
        $idProductAbstract = $productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract();

        $merchantProductCriteriaTransfer = (new MerchantProductCriteriaTransfer())
            ->addIdMerchant($idMerchant)
            ->setIdProductAbstract($idProductAbstract);

        $merchantProductTransfer = $constraint->getMerchantProductRepository()->findMerchantProduct($merchantProductCriteriaTransfer);

        if (!$merchantProductTransfer) {
            $this->context->addViolation(sprintf($constraint->getMessage(), $idProductAbstract, $idMerchant));
        }
    }
}
