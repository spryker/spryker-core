<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCategoryGui\Communication\Form\Validator\Constraints;

use InvalidArgumentException;
use Spryker\Zed\CmsSlotBlockCategoryGui\Communication\Form\CategorySlotBlockConditionForm;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CategoryConditionConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\CmsSlotBlockCategoryGui\Communication\Form\Validator\Constraints\CategoryConditionConstraint $constraint
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof CategoryConditionConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                CategoryConditionConstraint::class,
                get_class($constraint)
            ));
        }

        if ($value[CategorySlotBlockConditionForm::FIELD_ALL]) {
            return;
        }

        if (!$value[CategorySlotBlockConditionForm::FIELD_CATEGORY_IDS]) {
            $this->context->buildViolation($constraint->getMessage())
                ->addViolation();
        }
    }
}
