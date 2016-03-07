<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CodeExistsValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\Discount\Communication\Constraint\CodeExists $constraint The constraint for the validation
     *
     * @api
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        $voucherId = $constraint->getVoucherId();
        $discountQueryContainer = $constraint->getQueryContainer();
        $voucherEntity = $discountQueryContainer->queryVoucher($value)->findOne();

        if ($voucherEntity !== null) {
            if ($voucherId === null) {
                $this->addValidation($value, $constraint);
            }
            if ($voucherId !== $voucherEntity->getIdDiscountVoucher()) {
                $this->addValidation($value, $constraint);
            }
        }
    }

    /**
     * @param string $value
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\Discount\Communication\Constraint\CodeExists $constraint
     *
     * @return void
     */
    protected function addValidation($value, Constraint $constraint)
    {
        $this->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }

}
