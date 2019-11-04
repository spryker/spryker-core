<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Form\Validator\Constraints;

use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Form\ProductCategorySlotBlockConditionForm;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProductCategoryConditionConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Form\Validator\Constraints\ProductCategoryConditionConstraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if ($value[ProductCategorySlotBlockConditionForm::FIELD_ALL]) {
            return;
        }

        if (!$value[ProductCategorySlotBlockConditionForm::FIELD_PRODUCT_IDS] &&
            !$value[ProductCategorySlotBlockConditionForm::FIELD_CATEGORY_IDS]
        ) {
            $this->context->buildViolation($constraint->getMessage())
                ->addViolation();
        }
    }
}
