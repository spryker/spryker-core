<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AddIdsProductToDeAssignConstraintValidator extends ConstraintValidator
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
        if (!$constraint instanceof AddIdsProductToDeAssignConstraint) {
            throw new UnexpectedTypeException($constraint, AddIdsProductToDeAssignConstraint::class);
        }

        $assignedProducts = $constraint->getProductLabelFacade()
            ->findProductAbstractRelationsByIdProductLabel($value->getIdProductLabel());

        if (array_diff($value->getIdsProductAbstractToDeAssign(), $assignedProducts) !== []) {
            $this->context
                ->buildViolation($constraint->getMessage())
                ->addViolation();
        }
    }
}
