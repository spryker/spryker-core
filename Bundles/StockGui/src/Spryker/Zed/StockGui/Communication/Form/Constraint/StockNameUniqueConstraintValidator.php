<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class StockNameUniqueConstraintValidator extends ConstraintValidator
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
        if (!$constraint instanceof StockNameUniqueConstraint) {
            throw new UnexpectedTypeException($constraint, StockNameUniqueConstraint::class);
        }

        $stockTransfer = $constraint->getStockFacade()->findStockByName($value);
        if ($stockTransfer === null) {
            return;
        }

        if ($stockTransfer->getIdStock() === (int)$this->context->getRoot()->getViewData()->getIdStock()) {
            return;
        }

        $this->context
            ->buildViolation($constraint->message)
            ->addViolation();
    }
}
