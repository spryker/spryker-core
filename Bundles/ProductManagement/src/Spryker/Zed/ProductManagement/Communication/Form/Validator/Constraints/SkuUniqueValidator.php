<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SkuUniqueValidator extends ConstraintValidator
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

        /** @var \Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\SkuUnique $constraint */
        $this->validateUniqueness($value, $constraint);
    }

    /**
     * @param mixed $value
     * @param \Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\SkuUnique $constraint
     *
     * @return void
     */
    protected function validateUniqueness($value, SkuUnique $constraint)
    {
        if ($constraint->getProductFacade()->findProductConcreteIdBySku($value) !== null) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ sku }}', $value)
                ->addViolation();
        }
    }
}
