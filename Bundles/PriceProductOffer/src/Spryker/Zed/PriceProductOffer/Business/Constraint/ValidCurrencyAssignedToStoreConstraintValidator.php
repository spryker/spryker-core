<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Constraint;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidCurrencyAssignedToStoreConstraintValidator extends AbstractConstraintValidator
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof PriceProductTransfer) {
            throw new UnexpectedTypeException($value, PriceProductTransfer::class);
        }

        if (!$constraint instanceof ValidCurrencyAssignedToStoreConstraint) {
            throw new UnexpectedTypeException($constraint, ValidCurrencyAssignedToStoreConstraint::class);
        }
        $moneyValueTransfer = $value->getMoneyValueOrFail();

        $storeTransfer = $constraint->getStoreFacade()->getStoreById($moneyValueTransfer->getFkStore());

        if (!in_array($moneyValueTransfer->getCurrencyOrFail()->getCode(), $storeTransfer->getAvailableCurrencyIsoCodes(), true)) {
            $this->context->buildViolation($constraint->getMessage())
                ->setParameter('{{ currency }}', $moneyValueTransfer->getCurrency()->getName())
                ->setParameter('{{ store }}', $storeTransfer->getName())
                ->addViolation();
        }
    }
}
