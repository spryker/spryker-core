<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AddIdsProductToAssignConstraintValidator extends ConstraintValidator
{
    /**
     * @param \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer|null $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof AddIdsProductToAssignConstraint) {
            throw new UnexpectedTypeException($constraint, AddIdsProductToAssignConstraint::class);
        }

        $assignedProducts = $constraint->getProductLabelFacade()
            ->getProductAbstractIdsByIdProductLabel($value->getIdProductLabel());

        if (array_intersect($value->getIdsProductAbstractToAssign(), $assignedProducts) !== []) {
            $this->context
                ->buildViolation($constraint->getMessage())
                ->addViolation();
        }
    }
}
