<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Business\Validator\Constraint;

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
    public function validate($value, Constraint $constraint): void
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

        $this->checkFields($value, $constraint);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    protected function checkFields($value, Constraint $constraint): void
    {
        if (!$constraint instanceof TransferConstraint) {
            throw new UnexpectedTypeException($constraint, TransferConstraint::class);
        }

        $value = $value->toArray(false, true);
        foreach ($constraint->fields as $fieldName => $fieldConstraint) {
            $existsInArray = is_array($value) && array_key_exists($fieldName, $value);

            if (!$existsInArray) {
                $this->context->buildViolation($constraint->getMissingFieldsMessage())
                    ->atPath(sprintf('[%s]', $fieldName))
                    ->setParameter('{{ field }}', $this->formatValue($fieldName))
                    ->setInvalidValue(null)
                    ->addViolation();

                continue;
            }
            if ($fieldConstraint) {
                $this->context->getValidator()
                    ->inContext($this->context)
                    ->atPath(sprintf('[%s]', $fieldName))
                    ->validate($value[$fieldName], $fieldConstraint);
            }
        }
    }
}
