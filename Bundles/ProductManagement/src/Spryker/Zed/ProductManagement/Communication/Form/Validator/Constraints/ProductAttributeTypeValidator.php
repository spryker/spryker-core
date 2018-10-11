<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProductAttributeTypeValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductAttributeType $constraint
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
    protected function validateAttributeType($value, Constraint $constraint)
    {
        if (empty($value[$constraint->fields['checkbox']])) {
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
    protected function validateNumberType($value, Constraint $constraint)
    {
        if ($constraint->productManagementAttributeTransfer->getInputType() === $constraint->fields['type'] && !is_numeric($constraint->fields['input'])) {
            $this->buildViolation($constraint, $constraint->fields['type'], $value[$constraint->fields['input']]);
        }
    }

    /**
     * @param \Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductAttributeType $constraint
     * @param string $type
     * @param mixed $value
     *
     * @return void
     */
    protected function buildViolation(Constraint $constraint, string $type, $value)
    {
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ type }}', $constraint->fields['type'])
            ->addViolation();
    }
}
