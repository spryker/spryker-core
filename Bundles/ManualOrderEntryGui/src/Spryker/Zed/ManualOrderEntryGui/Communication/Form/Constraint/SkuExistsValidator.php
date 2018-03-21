<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class SkuExistsValidator extends ConstraintValidator
{
    /**
     * Checks if prodcut with this SKU exists.
     *
     * @param mixed $value The value that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint The constraint for the validation
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value->getSku()) {
            return;
        }

        if (!$constraint instanceof SkuExists) {
            throw new UnexpectedTypeException($constraint, SkuExists::class);
        }

        if (!$this->hasProductConcrete($value->getSku(), $constraint)) {
            $this->context
                ->buildViolation(sprintf('SKU not found'))
                ->atPath('sku')
                ->addViolation();
        }
    }

    /**
     * @param string $concreteSku
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Form\Constraint\SkuExists $constraint
     *
     * @return bool
     */
    protected function hasProductConcrete($concreteSku, SkuExists $constraint)
    {
        return $constraint->getProductFacade()->hasProductConcrete($concreteSku);
    }
}
