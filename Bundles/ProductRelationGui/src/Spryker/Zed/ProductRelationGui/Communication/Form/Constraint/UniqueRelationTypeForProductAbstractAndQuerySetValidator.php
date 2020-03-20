<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductRelationCriteriaTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductRelationTypeTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueRelationTypeForProductAbstractAndQuerySetValidator extends ConstraintValidator
{
    /**
     * Checks if the passed productRelationTransfer is valid.
     *
     * @param mixed|\Generated\Shared\Transfer\ProductRelationTransfer $value The productRelationTransfer that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint The constraint for the validation
     *
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            return;
        }

        if (!$constraint instanceof UniqueRelationTypeForProductAbstractAndQuerySet) {
            throw new UnexpectedTypeException($constraint, UniqueRelationTypeForProductAbstractAndQuerySet::class);
        }

        if ($this->hasProductRelationType($constraint, $value)) {
            $this->createViolationMessage($value);
        }
    }

    /**
     * @param \Spryker\Zed\ProductRelationGui\Communication\Form\Constraint\UniqueRelationTypeForProductAbstractAndQuerySet $uniqueRelationTypeForProductAbstract
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return bool
     */
    protected function hasProductRelationType(
        UniqueRelationTypeForProductAbstractAndQuerySet $uniqueRelationTypeForProductAbstract,
        ProductRelationTransfer $productRelationTransfer
    ) {
        $productRelationCriteriaTransfer = (new ProductRelationCriteriaTransfer())
            ->setFkProductAbstract($productRelationTransfer->getFkProductAbstract())
            ->setRelationTypeKey($productRelationTransfer->getProductRelationType()->getKey())
            ->setQuerySet($productRelationTransfer->getQuerySet());

        $resultProductRelationTransfer = $uniqueRelationTypeForProductAbstract->getProductRelationFacade()
            ->findProductRelationByCriteria($productRelationCriteriaTransfer);

        if (!$resultProductRelationTransfer) {
            return false;
        }

        if ((int)$resultProductRelationTransfer->getIdProductRelation() === (int)$productRelationTransfer->getIdProductRelation()) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $value
     *
     * @return void
     */
    protected function createViolationMessage(ProductRelationTransfer $value)
    {
        $this->context
            ->buildViolation(
                sprintf(
                    'Selected product already has "%s" relation type for the given rule.',
                    $value->getProductRelationType()->getKey()
                )
            )
            ->atPath(ProductRelationTransfer::PRODUCT_RELATION_TYPE . '.' . ProductRelationTypeTransfer::KEY)
            ->addViolation();
    }
}
