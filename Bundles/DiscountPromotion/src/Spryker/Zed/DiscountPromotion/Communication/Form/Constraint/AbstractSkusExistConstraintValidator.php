<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Form\Constraint;

use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AbstractSkusExistConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof AbstractSkusExistConstraint) {
            throw new UnexpectedTypeException($constraint, AbstractSkusExistConstraint::class);
        }

        $nonExistingSkus = $this->getNonExistingAbstractSkus($value, $constraint);
        if ($nonExistingSkus) {
            $this->context
                ->buildViolation($constraint->getMessage(implode(', ', $nonExistingSkus)))
                ->atPath(DiscountPromotionTransfer::ABSTRACT_SKUS)
                ->addViolation();
        }
    }

    /**
     * @param array<string> $abstractSkus
     * @param \Spryker\Zed\DiscountPromotion\Communication\Form\Constraint\AbstractSkusExistConstraint $constraint
     *
     * @return array<string>
     */
    protected function getNonExistingAbstractSkus(array $abstractSkus, AbstractSkusExistConstraint $constraint): array
    {
        $productAbstractTransfers = $constraint->getProductFacade()->getRawProductAbstractTransfersByAbstractSkus($abstractSkus);
        $existingSkus = $this->extractSkusFromProductAbstractTransfers($productAbstractTransfers);

        return array_diff($abstractSkus, $existingSkus);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductAbstractTransfer> $productAbstractTransfers
     *
     * @return array<string>
     */
    protected function extractSkusFromProductAbstractTransfers(array $productAbstractTransfers): array
    {
        $skus = [];
        foreach ($productAbstractTransfers as $productAbstractTransfer) {
            $skus[] = $productAbstractTransfer->getSku();
        }

        return $skus;
    }
}
