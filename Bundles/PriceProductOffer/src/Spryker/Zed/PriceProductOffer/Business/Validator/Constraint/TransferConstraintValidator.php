<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator\Constraint;

use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class TransferConstraintValidator extends ConstraintValidator
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $abstractTransfer
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @throws \Symfony\Component\Validator\Exception\UnexpectedValueException
     *
     * @return void
     */
    public function validate($abstractTransfer, Constraint $constraint)
    {
        if (!$constraint instanceof TransferConstraint) {
            throw new UnexpectedTypeException($constraint, TransferConstraint::class);
        }
        if ($abstractTransfer === null) {
            return;
        }
        if (!$abstractTransfer instanceof AbstractTransfer) {
            throw new UnexpectedValueException($abstractTransfer, AbstractTransfer::class);
        }

        if ($abstractTransfer instanceof PriceProductOfferTransfer) {
            $priceProductTransfers = $abstractTransfer->getProductOfferOrFail()->getPrices();

            foreach ($priceProductTransfers as $priceProductTransfer) {
                $this->checkFields($priceProductTransfer, $constraint);
            }

            return;
        }

        $this->checkFields($abstractTransfer, $constraint);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $abstractTransfer
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    protected function checkFields($abstractTransfer, Constraint $constraint)
    {
        if (!$constraint instanceof TransferConstraint) {
            throw new UnexpectedTypeException($constraint, TransferConstraint::class);
        }

        $transferData = $abstractTransfer->toArray(false, true);
        foreach ($constraint->fields as $field => $fieldConstraint) {
            $existsInArray = is_array($transferData) && array_key_exists($field, $transferData);

            if (!$existsInArray) {
                $this->context->buildViolation($constraint->getMissingFieldsMessage())
                    ->atPath('[' . $field . ']')
                    ->setParameter('{{ field }}', $this->formatValue($field))
                    ->setInvalidValue(null)
                    ->addViolation();

                continue;
            }
            if ($fieldConstraint) {
                $this->context->getValidator()
                    ->inContext($this->context)
                    ->atPath('[' . $field . ']')
                    ->validate($transferData[$field], $fieldConstraint);
            }
        }
    }
}
