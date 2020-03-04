<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ProductRelationKeyUniqueConstraintValidator extends ConstraintValidator
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
        if (!$constraint instanceof ProductRelationKeyUniqueConstraint) {
            throw new UnexpectedTypeException($constraint, ProductRelationKeyUniqueConstraint::CLASS_CONSTRAINT);
        }

        if ($value === null) {
            return;
        }

        $productRelationTransfer = $constraint->getProductRelationFacade()->findProductRelationByKey($value);

        if (!$productRelationTransfer) {
            return;
        }

        if ($productRelationTransfer->getIdProductRelation() === (int)$this->context->getRoot()->getViewData()->getIdProductRelation()) {
            return;
        }

        $this->context
            ->buildViolation($constraint->getMessage())
            ->addViolation();
    }
}
