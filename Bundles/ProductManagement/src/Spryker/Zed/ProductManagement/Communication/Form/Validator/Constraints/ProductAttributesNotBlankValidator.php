<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints;

use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\ProductConcreteSuperAttributeForm;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProductAttributesNotBlankValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\SkuUnique|\Symfony\Component\Validator\Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value === null || $value === '') {
            return;
        }

        $this->validateAttributeNotBlank($value, $constraint);
    }

    /**
     * @param mixed $value
     * @param \Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductAttributesNotBlank $constraint
     *
     * @return void
     */
    protected function validateAttributeNotBlank($value, ProductAttributesNotBlank $constraint)
    {
        foreach ($value as $attribute) {
            if (empty($attribute[ProductConcreteSuperAttributeForm::FIELD_CHECKBOX]) && !empty($attribute[ProductConcreteSuperAttributeForm::FIELD_DROPDOWN])
                || !empty($attribute[ProductConcreteSuperAttributeForm::FIELD_CHECKBOX]) && !empty($attribute[ProductConcreteSuperAttributeForm::FIELD_INPUT])) {
                return;
            }
        }

        $this->context->buildViolation($constraint->message)->addViolation();
    }
}
