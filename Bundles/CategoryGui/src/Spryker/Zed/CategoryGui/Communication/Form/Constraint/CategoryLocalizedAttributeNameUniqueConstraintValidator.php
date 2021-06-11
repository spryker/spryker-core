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
        if ($this->isCategoryNameValid($categoryTransfer, (string)$value, $constraint)) {
            return;
        }

        $this->context
            ->buildViolation($constraint->getMessage($value))
            ->addViolation();
    }

    /**
     * @param mixed $categoryTransfer
     * @param string $categoryName
     * @param \Spryker\Zed\CategoryGui\Communication\Form\Constraint\CategoryLocalizedAttributeNameUniqueConstraint $constraint
     *
     * @return bool
     */
    protected function isCategoryNameValid($categoryTransfer, string $categoryName, CategoryLocalizedAttributeNameUniqueConstraint $constraint): bool
    {
        if (!($categoryTransfer instanceof CategoryTransfer)) {
            return true;
        }

        if (!$categoryName) {
            return true;
        }

        return (!$constraint->getCategoryFacade()->checkSameLevelCategoryByNameExists($categoryName, $categoryTransfer));
    }
}
