<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator\Constraint;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class TransferConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @throws \Symfony\Component\Validator\Exception\UnexpectedValueException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof TransferConstraint) {
            throw new UnexpectedTypeException($constraint, TransferConstraint::class);
        }
        if ($value === null) {
            return;
        }
        if (!$value instanceof AbstractTransfer) {
            throw new UnexpectedValueException($value, AbstractTransfer::class);
        }
        $value = $value->toArray(false, true);
        foreach ($constraint->fields as $field => $fieldConstraint) {
            $existsInArray = is_array($value) && array_key_exists($field, $value);

            if (!$existsInArray) {
                $this->context->buildViolation($constraint->getMissingFieldsMessage())
                    ->atPath('[' . $field . ']')
                    ->setParameter('{{ field }}', $this->formatValue($field))
                    ->setInvalidValue(null)
                    ->addViolation();

                continue;
            }
            if (!empty($fieldConstraint)) {
                $this->context->getValidator()
                        ->inContext($this->context)
                        ->atPath('[' . $field . ']')
                        ->validate($value[$field], $fieldConstraint);
            }
        }
    }
}
