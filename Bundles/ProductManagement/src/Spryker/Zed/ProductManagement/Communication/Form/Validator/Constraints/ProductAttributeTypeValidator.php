<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints;

use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\ProductConcreteSuperAttributeForm;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProductAttributeTypeValidator extends ConstraintValidator
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

        $this->validateAttributeType($value, $constraint);
    }

    /**
     * @param mixed $value
     * @param \Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductAttributeType $constraint
     *
     * @return void
     */
    protected function validateAttributeType($value, ProductAttributeType $constraint)
    {
        if (empty($value[ProductConcreteSuperAttributeForm::FIELD_CHECKBOX])) {
            return;
        }

        $this->validateNumberType($value, $constraint);
    }

    /**
     * @param mixed $value
     * @param \Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductAttributeType $constraint
     *
     * @return void
     */
    protected function validateNumberType($value, ProductAttributeType $constraint)
    {
        if ($constraint->productManagementAttributeTransfer->getInputType() === ProductAttributeType::TYPE_NUMBER && !is_numeric($value[ProductConcreteSuperAttributeForm::FIELD_INPUT])) {
            $this->buildViloation($constraint, ProductAttributeType::TYPE_NUMBER, $value[ProductConcreteSuperAttributeForm::FIELD_INPUT]);
        }
    }

    /**
     * @param \Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductAttributeType $constraint
     * @param string $type
     * @param mixed $value
     *
     * @return void
     */
    protected function buildViloation(ProductAttributeType $constraint, string $type, $value)
    {
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ type }}', ProductAttributeType::TYPE_NUMBER)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
