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

class CategoryLocalizedAttributeNameUniqueConstraintValidator extends ConstraintValidator
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
        if (!$constraint instanceof CategoryLocalizedAttributeNameUniqueConstraint) {
            throw new UnexpectedTypeException($constraint, CategoryLocalizedAttributeNameUniqueConstraint::class);
        }

        $categoryTransfer = $this->context->getRoot()->getData();
        if (!($categoryTransfer instanceof CategoryTransfer) || !$value) {
            return;
        }

        if ($constraint->getCategoryFacade()->checkSameLevelCategoryByNameExists($value, $categoryTransfer)) {
            $this->context
                ->buildViolation(sprintf($constraint->message, $value))
                ->addViolation();
        }
    }
}
