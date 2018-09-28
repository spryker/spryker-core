<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestRequestValidator\Communication\Stub\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CurrencyValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, SymfonyConstraint $constraint): void
    {
        if (!$value) {
            return;
        }

        if (!$constraint instanceof Currency) {
            throw new UnexpectedTypeException($constraint, Currency::class);
        }

        if (!$this->hasCurrencyCode($value, $constraint)) {
            $this->context
                ->buildViolation(sprintf('Currency code  "%s" is not supported.', $value))
                ->atPath('name')
                ->addViolation();
        }
    }

    /**
     * @param string $isoCode
     * @param \SprykerTest\Zed\RestRequestValidator\Communication\Stub\Constraint\Currency $constraint
     *
     * @return bool
     */
    protected function hasCurrencyCode(string $isoCode, Currency $constraint): bool
    {
        return $constraint->isValidCurrencyIsoCode($isoCode);
    }
}
