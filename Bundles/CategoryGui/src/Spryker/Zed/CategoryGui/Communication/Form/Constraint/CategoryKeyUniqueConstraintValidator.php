<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\CategoryTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CategoryKeyUniqueConstraintValidator extends ConstraintValidator
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
        if (!$constraint instanceof CategoryKeyUniqueConstraint) {
            throw new UnexpectedTypeException($constraint, CategoryKeyUniqueConstraint::class);
        }

        $categoryTransfer = $this->context->getRoot()->getData();
        if (!($categoryTransfer instanceof CategoryTransfer) || !$value) {
            return;
        }

        if ($constraint->getCategoryGuiRepositoryFacade()->isCategoryKeyUsed($value)) {
            $this->context
                ->buildViolation(sprintf($constraint->message, $value))
                ->addViolation();
        }
    }
}
