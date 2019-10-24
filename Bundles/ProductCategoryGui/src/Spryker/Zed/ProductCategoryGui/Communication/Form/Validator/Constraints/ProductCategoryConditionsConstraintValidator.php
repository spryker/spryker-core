<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryGui\Communication\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProductCategoryConditionsConstraintValidator extends ConstraintValidator
{
    protected const CONDITION_KEY_ALL = 'all';
    protected const CONDITION_KEY_PRODUCT_IDS = 'productIds';
    protected const CONDITION_KEY_CATEGORY_IDS = 'categoryIds';

    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if ($value[static::CONDITION_KEY_ALL]) {
            return;
        }

        if (!$value[static::CONDITION_KEY_PRODUCT_IDS] || !$value[static::CONDITION_KEY_CATEGORY_IDS]) {
            $this->context->buildViolation($constraint->getMessage())
                ->addViolation();
        }
    }
}
